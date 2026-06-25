<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\SubscriptionPlan;
use App\Services\StudentCourseAccessManager;
use App\Services\PaystackService;
use App\Services\SubscriptionManager;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class SubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        return view('subscriptions.index', [
            'plans' => SubscriptionPlan::query()->active()->ordered()->get(),
            'activeSubscription' => $request->user()?->activeSubscription(),
        ]);
    }

    public function subscribe(
        Request $request,
        SubscriptionPlan $plan,
        PaystackService $paystack,
    ): RedirectResponse {
        abort_unless($plan->is_active && $plan->paystack_plan_code, 404);

        if ($request->user()->hasActiveSubscription()) {
            return to_route('subscriptions.index')->with('status', 'You already have an active subscription.');
        }

        $data = $request->validate([
            'selected_instrument_id' => ['required', 'uuid', 'exists:instruments,id'],
        ]);

        if ($request->user()->selected_instrument_id && $request->user()->selected_instrument_id !== $data['selected_instrument_id']) {
            return to_route('dashboard')->with('status', 'Your subscription is already linked to your selected instrument. Please contact admin if this was a mistake.');
        }

        $reference = 'VMA-'.now()->format('YmdHis').'-'.Str::upper(Str::random(12));
        $transaction = PaymentTransaction::query()->create([
            'user_id' => $request->user()->id,
            'subscription_plan_id' => $plan->id,
            'selected_instrument_id' => $data['selected_instrument_id'],
            'reference' => $reference,
            'amount' => $plan->amountInKobo(),
            'currency' => 'NGN',
            'status' => PaymentTransaction::STATUS_PENDING,
            'gateway' => 'paystack',
        ]);

        try {
            $response = $paystack->initializeSubscription($request->user(), $plan, $reference);

            return redirect()->away(data_get($response, 'data.authorization_url'));
        } catch (Throwable $exception) {
            $transaction->update([
                'status' => PaymentTransaction::STATUS_FAILED,
                'raw_response' => ['message' => $exception->getMessage()],
            ]);

            report($exception);

            return to_route('subscriptions.failed')->with('error', $exception->getMessage());
        }
    }

    public function callback(
        Request $request,
        PaystackService $paystack,
        SubscriptionManager $subscriptions,
        StudentCourseAccessManager $courseAccess,
    ): View|RedirectResponse {
        $reference = (string) ($request->query('reference') ?: $request->query('trxref'));

        if ($reference === '') {
            return view('subscriptions.failed', ['message' => 'The payment reference was not returned.']);
        }

        if (! preg_match('/\A[A-Za-z0-9._-]{1,100}\z/', $reference)) {
            Log::warning('Paystack callback rejected an invalid payment reference.', [
                'reference_length' => strlen($reference),
            ]);

            return view('subscriptions.failed', ['message' => 'The payment reference is invalid.']);
        }

        $transaction = PaymentTransaction::query()->where('reference', $reference)->first();

        if (! $transaction) {
            Log::warning('Paystack callback reference was not found.', [
                'reference' => $reference,
            ]);

            return view('subscriptions.failed', ['message' => 'This payment reference could not be found.']);
        }

        try {
            $response = $paystack->verifyTransaction($reference);
            $subscription = $subscriptions->confirmSuccessfulTransaction($transaction, data_get($response, 'data', []));

            if ($subscription->instrument) {
                $courseAccess->initializeForInstrument($subscription->user, $subscription->instrument);
            }

            return to_route('dashboard')->with('status', 'Payment successful. Your subscription is now active.');
        } catch (Throwable $exception) {
            if ($transaction->status !== PaymentTransaction::STATUS_SUCCESS) {
                $transaction->update([
                    'status' => PaymentTransaction::STATUS_FAILED,
                    'raw_response' => ['message' => $exception->getMessage()],
                ]);
            }

            Log::warning('Paystack payment verification failed.', [
                'reference' => $reference,
                'transaction_status' => $transaction->status,
                'message' => $exception->getMessage(),
            ]);

            report($exception);

            return view('subscriptions.failed', ['message' => 'We could not verify this payment. Please try again or contact support.']);
        }
    }

    public function failed(Request $request): View
    {
        return view('subscriptions.failed', [
            'message' => $request->session()->get('error', 'Your payment was not completed. You can safely try again.'),
        ]);
    }
}
