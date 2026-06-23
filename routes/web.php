<?php

use App\Http\Controllers\AcademyController;
use App\Http\Controllers\ContinueLearningController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LessonProgressController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaystackWebhookController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/store', [StoreController::class, 'index'])->name('store.index');
Route::get('/store/category/{category:slug}', [StoreController::class, 'category'])->name('store.categories.show');
Route::get('/store/{product:slug}', [StoreController::class, 'show'])->name('store.products.show');
Route::get('/pricing', [SubscriptionController::class, 'index'])->name('subscriptions.index');
Route::get('/subscriptions/callback', [SubscriptionController::class, 'callback'])->name('subscriptions.callback');
Route::get('/subscriptions/failed', [SubscriptionController::class, 'failed'])->name('subscriptions.failed');
Route::post('/paystack/webhook', PaystackWebhookController::class)->name('paystack.webhook');

Route::get('/dashboard', StudentDashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/subscriptions/{plan:slug}/subscribe', [SubscriptionController::class, 'subscribe'])
        ->middleware('verified')
        ->name('subscriptions.subscribe');
    Route::get('/academy', [AcademyController::class, 'index'])->name('academy.index');
    Route::get('/continue-learning', ContinueLearningController::class)->name('academy.continue');
    Route::get('/academy/{instrument:slug}', [AcademyController::class, 'instrument'])->name('academy.instrument');
    Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->middleware('subscription.active')->name('courses.show');
    Route::get('/lessons/{lesson:slug}', [LessonController::class, 'show'])->middleware('subscription.active')->name('lessons.show');
    Route::post('/lessons/{lesson}/progress', [LessonProgressController::class, 'store'])->middleware('subscription.active')->name('lessons.progress.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
