<?php

namespace App\Services;

use App\Models\PaymentTransaction;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SubscriptionManager
{
    public function confirmSuccessfulTransaction(PaymentTransaction $transaction, array $paystackData): UserSubscription
    {
        return DB::transaction(function () use ($transaction, $paystackData): UserSubscription {
            $transaction = PaymentTransaction::query()->lockForUpdate()->findOrFail($transaction->id);

            if (($paystackData['status'] ?? null) !== 'success') {
                throw new RuntimeException('Paystack did not confirm this transaction as successful.');
            }

            if (($paystackData['reference'] ?? null) !== $transaction->reference) {
                throw new RuntimeException('The verified payment reference does not match this transaction.');
            }

            if ((int) ($paystackData['amount'] ?? 0) !== $transaction->amount) {
                throw new RuntimeException('The verified payment amount does not match the selected plan.');
            }

            if (strtoupper((string) ($paystackData['currency'] ?? '')) !== $transaction->currency) {
                throw new RuntimeException('The verified payment currency does not match the selected plan.');
            }

            $paidAt = isset($paystackData['paid_at']) ? Carbon::parse($paystackData['paid_at']) : now();
            $transaction->update([
                'status' => PaymentTransaction::STATUS_SUCCESS,
                'paid_at' => $paidAt,
                'raw_response' => $paystackData,
            ]);

            $plan = $transaction->plan()->firstOrFail();
            $verifiedPlanCode = data_get($paystackData, 'plan.plan_code');

            if ($verifiedPlanCode && $verifiedPlanCode !== $plan->paystack_plan_code) {
                throw new RuntimeException('The verified Paystack plan does not match the selected plan.');
            }

            $customerCode = data_get($paystackData, 'customer.customer_code');
            $subscriptionCode = data_get($paystackData, 'subscription.subscription_code')
                ?? data_get($paystackData, 'subscription_code');
            $emailToken = data_get($paystackData, 'subscription.email_token')
                ?? data_get($paystackData, 'email_token');

            $subscription = UserSubscription::query()->updateOrCreate(
                $subscriptionCode ? ['paystack_subscription_code' => $subscriptionCode] : [
                    'user_id' => $transaction->user_id,
                    'subscription_plan_id' => $plan->id,
                ],
                [
                    'user_id' => $transaction->user_id,
                    'subscription_plan_id' => $plan->id,
                    'paystack_customer_code' => $customerCode,
                    'paystack_subscription_code' => $subscriptionCode,
                    'paystack_email_token' => $emailToken,
                    'status' => UserSubscription::STATUS_ACTIVE,
                    'starts_at' => $paidAt,
                    'ends_at' => $this->calculateEndDate($paidAt, $plan),
                    'cancelled_at' => null,
                ],
            );

            UserSubscription::query()
                ->where('user_id', $transaction->user_id)
                ->whereKeyNot($subscription->id)
                ->where('status', UserSubscription::STATUS_ACTIVE)
                ->update(['status' => UserSubscription::STATUS_INACTIVE]);

            return $subscription;
        });
    }

    public function calculateEndDate(Carbon $startsAt, SubscriptionPlan $plan): Carbon
    {
        return match ($plan->billing_interval) {
            SubscriptionPlan::INTERVAL_MONTHLY => $startsAt->copy()->addMonth(),
            SubscriptionPlan::INTERVAL_QUARTERLY => $startsAt->copy()->addMonths(3),
            SubscriptionPlan::INTERVAL_ANNUALLY => $startsAt->copy()->addYear(),
            default => $startsAt->copy()->addMonth(),
        };
    }
}
