<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\PaymentTransaction;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SubscriptionPaymentsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.paystack.secret_key', 'sk_test_subscription_key');
        config()->set('services.paystack.payment_url', 'https://api.paystack.co');
        config()->set('services.paystack.callback_url', 'http://localhost/subscriptions/callback');
    }

    public function test_active_plans_are_visible_on_the_pricing_page(): void
    {
        $plan = $this->createPlan();

        $this->get(route('subscriptions.index'))
            ->assertOk()
            ->assertSee($plan->name)
            ->assertSee('₦5,000');
    }

    public function test_user_can_initialize_a_paystack_subscription_checkout(): void
    {
        $user = User::factory()->create();
        $plan = $this->createPlan();

        Http::fake([
            'https://api.paystack.co/transaction/initialize' => Http::response([
                'status' => true,
                'data' => [
                    'authorization_url' => 'https://checkout.paystack.com/test-reference',
                    'reference' => 'test-reference',
                ],
            ]),
        ]);

        $this->actingAs($user)
            ->post(route('subscriptions.subscribe', $plan))
            ->assertRedirect('https://checkout.paystack.com/test-reference');

        $this->assertDatabaseHas('payment_transactions', [
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'amount' => 500000,
            'status' => PaymentTransaction::STATUS_PENDING,
        ]);
    }

    public function test_verified_callback_activates_the_subscription(): void
    {
        $user = User::factory()->create();
        $plan = $this->createPlan();
        $transaction = PaymentTransaction::query()->create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'reference' => 'VMA-CALLBACK-TEST',
            'amount' => 500000,
            'currency' => 'NGN',
            'status' => PaymentTransaction::STATUS_PENDING,
            'gateway' => 'paystack',
        ]);

        Http::fake([
            'https://api.paystack.co/transaction/verify/VMA-CALLBACK-TEST' => Http::response([
                'status' => true,
                'data' => $this->successfulPaystackData($transaction->reference, $plan),
            ]),
        ]);

        $this->get(route('subscriptions.callback', ['reference' => $transaction->reference]))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('status', 'Payment successful. Your subscription is now active.');

        $this->assertDatabaseHas('payment_transactions', [
            'reference' => $transaction->reference,
            'status' => PaymentTransaction::STATUS_SUCCESS,
        ]);
        $this->assertDatabaseHas('user_subscriptions', [
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'status' => UserSubscription::STATUS_ACTIVE,
        ]);
    }

    public function test_signed_webhook_is_idempotent(): void
    {
        $user = User::factory()->create();
        $plan = $this->createPlan();
        PaymentTransaction::query()->create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'reference' => 'VMA-WEBHOOK-TEST',
            'amount' => 500000,
            'currency' => 'NGN',
            'status' => PaymentTransaction::STATUS_PENDING,
            'gateway' => 'paystack',
        ]);

        $payload = json_encode([
            'event' => 'charge.success',
            'data' => $this->successfulPaystackData('VMA-WEBHOOK-TEST', $plan),
        ], JSON_THROW_ON_ERROR);
        $signature = hash_hmac('sha512', $payload, 'sk_test_subscription_key');

        $server = [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_PAYSTACK_SIGNATURE' => $signature,
        ];

        $this->call('POST', route('paystack.webhook'), [], [], [], $server, $payload)->assertOk();
        $this->call('POST', route('paystack.webhook'), [], [], [], $server, $payload)->assertOk();

        $this->assertDatabaseCount('payment_transactions', 1);
        $this->assertDatabaseCount('user_subscriptions', 1);
    }

    public function test_premium_lessons_require_an_active_subscription(): void
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create(['is_premium' => true, 'is_free_preview' => false]);

        $this->actingAs($user)
            ->get(route('lessons.show', $lesson))
            ->assertRedirect(route('academy.instrument', $lesson->course->instrument))
            ->assertSessionHas('status', 'Subscribe to continue learning this course.');
    }

    public function test_instrument_course_page_displays_courses_and_subscription_plans(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $plan = $this->createPlan();

        $this->actingAs($user)
            ->get(route('academy.instrument', $course->instrument))
            ->assertOk()
            ->assertSee($course->title)
            ->assertSee($plan->name);
    }

    public function test_authenticated_navbar_does_not_show_pricing_link(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('academy.index'))
            ->assertOk()
            ->assertDontSee('Pricing');
    }

    public function test_active_subscriber_can_open_premium_lessons(): void
    {
        $user = User::factory()->create();
        $plan = $this->createPlan();
        UserSubscription::query()->create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'status' => UserSubscription::STATUS_ACTIVE,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ]);
        $lesson = Lesson::factory()->create(['is_premium' => true, 'is_free_preview' => false]);

        $this->actingAs($user)
            ->get(route('lessons.show', $lesson))
            ->assertOk();
    }

    public function test_free_preview_remains_available_inside_a_premium_course(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['is_premium' => true]);
        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'is_premium' => true,
            'is_free_preview' => true,
        ]);

        $this->actingAs($user)
            ->get(route('lessons.show', $lesson))
            ->assertOk();
    }

    private function createPlan(): SubscriptionPlan
    {
        return SubscriptionPlan::query()->create([
            'name' => 'Monthly Plan',
            'slug' => 'monthly-plan',
            'description' => 'Premium music learning access.',
            'price' => 5000,
            'billing_interval' => SubscriptionPlan::INTERVAL_MONTHLY,
            'paystack_plan_code' => 'PLN_test_monthly',
            'is_active' => true,
            'display_order' => 1,
        ]);
    }

    private function successfulPaystackData(string $reference, SubscriptionPlan $plan): array
    {
        return [
            'status' => 'success',
            'reference' => $reference,
            'amount' => $plan->amountInKobo(),
            'currency' => 'NGN',
            'paid_at' => now()->toIso8601String(),
            'customer' => [
                'customer_code' => 'CUS_test_customer',
            ],
            'subscription' => [
                'subscription_code' => 'SUB_test_subscription',
                'email_token' => 'email-token',
            ],
        ];
    }
}
