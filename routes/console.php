<?php

use App\Models\UserSubscription;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function (): void {
    UserSubscription::query()
        ->where('status', UserSubscription::STATUS_ACTIVE)
        ->whereNotNull('ends_at')
        ->where('ends_at', '<=', now())
        ->update(['status' => UserSubscription::STATUS_EXPIRED]);
})->hourly()->name('expire-ended-subscriptions')->withoutOverlapping();
