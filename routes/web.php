<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\VisaController as AdminVisaController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\App;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [HomeController::class, 'contact'])->name('contact.send');
Route::post('/subscribe', [HomeController::class, 'subscribe'])->name('newsletter.subscribe');

// Services routes
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
Route::post('/services/{service}/book', [ServiceController::class, 'book'])->name('services.book')->middleware('auth');

// Authentication routes
Auth::routes();

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Booking management
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::delete('/bookings/{booking}', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

// Customer Routes
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
        Route::get('dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::get('profile', [CustomerController::class, 'profile'])->name('profile');
        Route::put('profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
        Route::put('profile/password', [CustomerController::class, 'updatePassword'])->name('password.update');
        Route::put('profile/photo', [CustomerController::class, 'updatePhoto'])->name('profile.photo');
        Route::put('profile/notifications', [CustomerController::class, 'updateNotificationPreferences'])->name('notifications.preferences');
        Route::post('logout', [CustomerAuthController::class, 'logout'])->name('logout');

        // Bookings
        Route::resource('bookings', BookingController::class);
        
        // Visas
        Route::resource('visas', VisaController::class);
        
        // Transactions
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions');
        
        // Notifications
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
        Route::put('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    });

    // Customer Visa Routes
    Route::middleware(['auth:customer'])->prefix('customer')->name('customer.')->group(function () {
        Route::resource('visas', Customer\VisaController::class);
        Route::get('visas/documents/{document}/download', [Customer\VisaController::class, 'downloadDocument'])
            ->name('visas.documents.download');
    });

    // Customer Booking Routes
    Route::middleware(['auth:customer'])->prefix('customer')->name('customer.')->group(function () {
        Route::resource('bookings', Customer\BookingController::class);
        Route::post('bookings/{booking}/cancel', [Customer\BookingController::class, 'cancel'])
            ->name('bookings.cancel');
        Route::get('bookings/tickets/{ticket}/download', [Customer\BookingController::class, 'downloadTicket'])
            ->name('bookings.tickets.download');
    });

    // Customer Passport Routes
    Route::middleware(['auth:customer'])->prefix('customer')->name('customer.')->group(function () {
        Route::resource('passports', Customer\PassportController::class);
        Route::get('passports/documents/{document}/download', [Customer\PassportController::class, 'downloadDocument'])
            ->name('passports.documents.download');
    });

    // Customer Payment Routes
    Route::middleware(['auth:customer'])->prefix('customer')->name('customer.')->group(function () {
        Route::get('payments', [Customer\PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{payment}', [Customer\PaymentController::class, 'show'])->name('payments.show');
        Route::post('payments/process/{transaction}', [Customer\PaymentController::class, 'process'])->name('payments.process');
        Route::post('payments/{payment}/refund', [Customer\PaymentController::class, 'requestRefund'])->name('payments.refund');
        Route::get('invoices/{invoice}', [Customer\InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('invoices/{invoice}/download', [Customer\InvoiceController::class, 'download'])->name('invoices.download');
    });
});

// Frontend Routes
Route::get('/page/{slug}', [HomeController::class, 'page'])->name('pages.show');

// Language Switcher
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session()->put('locale', $locale);
        App::setLocale($locale);
    }
    return redirect()->back();
})->name('language.switch');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
});

// CMS Routes
//Route::get('/', [HomeController::class, 'index'])->name('home');
//Route::get('/page/{slug}', [HomeController::class, 'page'])->name('pages.show');

// Admin Visa Routes
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('visas', AdminVisaController::class);
    Route::put('visas/{visa}/status', [AdminVisaController::class, 'updateStatus'])
        ->name('visas.status');
    Route::get('visas/documents/{document}/download', [AdminVisaController::class, 'downloadDocument'])
        ->name('visas.documents.download');
    Route::get('visas/documents/{document}/preview', [AdminVisaController::class, 'previewDocument'])
        ->name('visas.documents.preview');
    // Services Management
    Route::resource('services', AdminServiceController::class);
    Route::post('services/{service}/toggle-featured', [AdminServiceController::class, 'toggleFeatured'])->name('services.toggle-featured');
    Route::post('services/{service}/toggle-active', [AdminServiceController::class, 'toggleActive'])->name('services.toggle-active');
    // CMS Management
    Route::resource('pages', PageController::class);
    Route::post('pages/order', [PageController::class, 'updateOrder'])->name('pages.order');
    Route::get('pages/{page}/preview', [PageController::class, 'preview'])->name('pages.preview');
    
    Route::post('pages/{page}/sections', [PageSectionController::class, 'store'])->name('pages.sections.store');
    Route::put('pages/{page}/sections/{section}', [PageSectionController::class, 'update'])->name('pages.sections.update');
    Route::delete('pages/{page}/sections/{section}', [PageSectionController::class, 'destroy'])->name('pages.sections.destroy');
    Route::post('pages/{page}/sections/order', [PageSectionController::class, 'updateOrder'])->name('pages.sections.order');
});

// Admin Booking Routes
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('bookings', AdminBookingController::class);
    Route::put('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])
        ->name('bookings.status');
    Route::get('bookings-report', [AdminBookingController::class, 'report'])
        ->name('bookings.report');
});

// Admin Passport Routes
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('passports', Admin\PassportController::class);
    Route::put('passports/{passport}/status', [Admin\PassportController::class, 'updateStatus'])
        ->name('passports.status');
    Route::get('passports-report', [Admin\PassportController::class, 'report'])
        ->name('passports.report');
    Route::get('passports/documents/{document}/download', [Admin\PassportController::class, 'downloadDocument'])
        ->name('passports.documents.download');
    Route::get('passports/documents/{document}/preview', [Admin\PassportController::class, 'previewDocument'])
        ->name('passports.documents.preview');
});

// Admin Payment Routes
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::post('payments/{payment}/refund', [AdminPaymentController::class, 'processRefund'])->name('payments.refund');
    Route::get('payments-report', [AdminPaymentController::class, 'report'])->name('payments.report');
});

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::resource('customers', CustomerController::class);
    Route::resource('visas', AdminVisaController::class);
    Route::resource('bookings', AdminBookingController::class);
    Route::resource('payments', AdminPaymentController::class);
});
