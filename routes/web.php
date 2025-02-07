<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Customer\TransactionController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\VisaController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\NotificationController;
use App\Http\Controllers\Customer\CustomerBookingController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\VisaController as AdminVisaController;
use Illuminate\Support\Facades\App;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{destination}', [DestinationController::class, 'show'])->name('destinations.show');

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/
Route::prefix('customer')->name('customer.')->group(function () {
    // Guest routes
    Route::middleware('guest:customer')->group(function () {
        Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [CustomerAuthController::class, 'login']);
        Route::get('register', [CustomerAuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [CustomerAuthController::class, 'register']);
    });

    // Authenticated routes
    Route::middleware('auth:customer')->group(function () {
        Route::post('logout', [CustomerAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');

        // Profile
        Route::get('profile', [CustomerController::class, 'profile'])->name('profile');
        Route::put('profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
        Route::put('profile/password', [CustomerController::class, 'updatePassword'])->name('password.update');
        Route::put('/profile/photo', [CustomerController::class, 'updatePhoto'])->name('profile.photo');
        Route::put('/profile/notifications', [CustomerController::class, 'updateNotificationPreferences'])->name('profile.notifications');
        Route::get('profile/edit', [CustomerController::class, 'edit'])->name('profile.edit');  // Add this line
        // Bookings
        Route::resource('bookings', CustomerBookingController::class);
        Route::post('bookings/{booking}/cancel', [CustomerBookingController::class, 'cancel'])
            ->name('bookings.cancel');

            // Transactions
Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        // Visas
        Route::resource('visas', VisaController::class);
// Notifications
Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
Route::put('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::put('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
Route::get('notifications/preferences', [NotificationController::class, 'preferences'])->name('notifications.preferences');
Route::put('notifications/preferences', [NotificationController::class, 'updatePreferences'])->name('notifications.preferences.update');
        // Payments
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login']);

     // Add these new routes for password reset
     Route::get('password/reset', [AdminAuthController::class, 'showLinkRequestForm'])->name('password.request');
     Route::post('password/email', [AdminAuthController::class, 'sendResetLinkEmail'])->name('password.email');
     Route::get('password/reset/{token}', [AdminAuthController::class, 'showResetForm'])->name('password.reset');
     Route::post('password/reset', [AdminAuthController::class, 'reset'])->name('password.update');

    });

    // Authenticated routes
    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');

        // User Management
           Route::resource('users', UserController::class);

        // Services Management
        Route::resource('services', AdminServiceController::class);
        Route::post('services/{service}/toggle-featured', [AdminServiceController::class, 'toggleFeatured'])
            ->name('services.toggle-featured');

        // Bookings Management
        Route::resource('bookings', AdminBookingController::class);
        Route::put('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])
            ->name('bookings.status');

        // Visa Management
        Route::resource('visas', AdminVisaController::class);
        Route::put('visas/{visa}/status', [AdminVisaController::class, 'updateStatus'])
            ->name('visas.status');

        // Payments Management
        Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
        Route::get('payments-report', [AdminPaymentController::class, 'report'])->name('payments.report');

        // Customer Management
        Route::resource('customers', CustomerController::class);
    });
});

// Language Switcher
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session()->put('locale', $locale);
        App::setLocale($locale);
    }
    return redirect()->back();
})->name('language.switch');
