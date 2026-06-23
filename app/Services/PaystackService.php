<?php

namespace App\Services;

use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PaystackService
{
    public function initializeSubscription(User $user, SubscriptionPlan $plan, string $reference): array
    {
        $response = $this->client()->post('/transaction/initialize', [
            'email' => $user->email,
            'amount' => $plan->amountInKobo(),
            'currency' => 'NGN',
            'plan' => $plan->paystack_plan_code,
            'reference' => $reference,
            'callback_url' => config('services.paystack.callback_url') ?: route('subscriptions.callback'),
            'metadata' => [
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'plan_name' => $plan->name,
            ],
        ]);

        if (! $response->successful() || ! $response->json('status')) {
            throw new RuntimeException($response->json('message', 'Paystack could not initialize the payment.'));
        }

        if (! filter_var($response->json('data.authorization_url'), FILTER_VALIDATE_URL)) {
            throw new RuntimeException('Paystack did not return a valid checkout URL.');
        }

        return $response->json();
    }

    public function verifyTransaction(string $reference): array
    {
        $response = $this->client()->get('/transaction/verify/'.urlencode($reference));

        if (! $response->successful() || ! $response->json('status')) {
            throw new RuntimeException($response->json('message', 'Paystack could not verify the payment.'));
        }

        return $response->json();
    }

    public function hasValidSignature(string $payload, ?string $signature): bool
    {
        $secret = (string) config('services.paystack.secret_key');

        if ($secret === '' || ! $signature) {
            return false;
        }

        return hash_equals(hash_hmac('sha512', $payload, $secret), $signature);
    }

    private function client(): PendingRequest
    {
        $secret = (string) config('services.paystack.secret_key');

        if ($secret === '') {
            throw new RuntimeException('Paystack is not configured. Add the Paystack keys to the environment file.');
        }

        return Http::baseUrl(rtrim((string) config('services.paystack.payment_url'), '/'))
            ->withToken($secret)
            ->acceptJson()
            ->asJson()
            ->timeout(30);
    }
}
