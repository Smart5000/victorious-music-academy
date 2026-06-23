<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSubscription;
use App\Services\PaystackService;
use App\Services\SubscriptionManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class PaystackWebhookController extends Controller
{
    public function __invoke(
        Request $request,
        PaystackService $paystack,
        SubscriptionManager $subscriptions,
    ): JsonResponse {
        $payload = $request->getContent();

        if (! $paystack->hasValidSignature($payload, $request->header('x-paystack-signature'))) {
            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        $event = json_decode($payload, true);

        if (! is_array($event) || ! isset($event['event'], $event['data'])) {
            return response()->json(['message' => 'Invalid payload.'], 422);
        }

        try {
            match ($event['event']) {
                'charge.success' => $this->handleSuccessfulCharge($event['data'], $subscriptions),
                'charge.failed' => $this->handleFailedCharge($event['data']),
                'subscription.create', 'subscription.enable' => $this->handleSubscriptionEnabled($event['data']),
                'subscription.disable' => $this->handleSubscriptionDisabled($event['data']),
                'invoice.payment_failed' => $this->handleSubscriptionFailed($event['data']),
                default => null,
            };
        } catch (Throwable $exception) {
            Log::error('Paystack webhook processing failed.', [
                'event' => $event['event'],
                'message' => $exception->getMessage(),
            ]);

            return response()->json(['message' => 'Webhook processing failed.'], 500);
        }

        return response()->json(['received' => true]);
    }

    private function handleSuccessfulCharge(array $data, SubscriptionManager $subscriptions): void
    {
        $reference = (string) ($data['reference'] ?? '');

        if ($reference === '') {
            return;
        }

        $transaction = PaymentTransaction::query()->where('reference', $reference)->first();

        if (! $transaction) {
            $subscription = $this->findSubscription($data);

            if (! $subscription) {
                return;
            }

            $transaction = PaymentTransaction::query()->firstOrCreate(
                ['reference' => $reference],
                [
                    'user_id' => $subscription->user_id,
                    'subscription_plan_id' => $subscription->subscription_plan_id,
                    'amount' => (int) ($data['amount'] ?? 0),
                    'currency' => strtoupper((string) ($data['currency'] ?? 'NGN')),
                    'status' => PaymentTransaction::STATUS_PENDING,
                    'gateway' => 'paystack',
                ],
            );
        }

        if ($transaction->status !== PaymentTransaction::STATUS_SUCCESS) {
            $subscriptions->confirmSuccessfulTransaction($transaction, $data);
        }
    }

    private function handleFailedCharge(array $data): void
    {
        $transaction = PaymentTransaction::query()
            ->where('reference', $data['reference'] ?? '')
            ->where('status', '!=', PaymentTransaction::STATUS_SUCCESS)
            ->first();

        $transaction?->update([
                'status' => PaymentTransaction::STATUS_FAILED,
                'raw_response' => $data,
        ]);
    }

    private function handleSubscriptionEnabled(array $data): void
    {
        $subscriptionCode = $data['subscription_code'] ?? null;
        $planCode = data_get($data, 'plan.plan_code') ?? data_get($data, 'plan_code');
        $plan = SubscriptionPlan::query()->where('paystack_plan_code', $planCode)->first();
        $user = User::query()->where('email', data_get($data, 'customer.email'))->first();

        if (! $subscriptionCode || ! $plan || ! $user) {
            return;
        }

        $subscription = UserSubscription::query()->updateOrCreate(
            ['paystack_subscription_code' => $subscriptionCode],
            [
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'paystack_customer_code' => data_get($data, 'customer.customer_code'),
                'paystack_email_token' => $data['email_token'] ?? null,
                'status' => UserSubscription::STATUS_ACTIVE,
                'starts_at' => now(),
                'ends_at' => isset($data['next_payment_date']) ? $data['next_payment_date'] : null,
                'cancelled_at' => null,
            ],
        );

        UserSubscription::query()
            ->where('user_id', $user->id)
            ->whereKeyNot($subscription->id)
            ->where('status', UserSubscription::STATUS_ACTIVE)
            ->update(['status' => UserSubscription::STATUS_INACTIVE]);
    }

    private function handleSubscriptionDisabled(array $data): void
    {
        UserSubscription::query()
            ->where('paystack_subscription_code', $data['subscription_code'] ?? '')
            ->update([
                'status' => UserSubscription::STATUS_CANCELLED,
                'cancelled_at' => now(),
                'ends_at' => now(),
            ]);
    }

    private function handleSubscriptionFailed(array $data): void
    {
        $subscriptionCode = data_get($data, 'subscription.subscription_code')
            ?? data_get($data, 'subscription_code');

        UserSubscription::query()
            ->where('paystack_subscription_code', $subscriptionCode)
            ->update(['status' => UserSubscription::STATUS_FAILED]);
    }

    private function findSubscription(array $data): ?UserSubscription
    {
        $subscriptionCode = data_get($data, 'subscription.subscription_code')
            ?? data_get($data, 'subscription_code');

        if ($subscriptionCode) {
            return UserSubscription::query()
                ->where('paystack_subscription_code', $subscriptionCode)
                ->first();
        }

        return UserSubscription::query()
            ->where('paystack_customer_code', data_get($data, 'customer.customer_code'))
            ->active()
            ->latest()
            ->first();
    }
}
