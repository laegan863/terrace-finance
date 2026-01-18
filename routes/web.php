<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TerraceFinanceAuthController;
use App\Http\Controllers\TerraceFinanceLeadController;
use App\Http\Controllers\TerraceFinanceOfferController;
use App\Http\Controllers\TerraceFinanceInvoiceController;
use App\Http\Controllers\TerraceFinanceApplicationController;
use App\Http\Controllers\TerraceFinancePricingFactorController;
use App\Http\Controllers\TerraceFinanceApplicationStatusController;
use App\Http\Controllers\TerraceFinanceStatusNotificationController;

// Route::get('/', function () {
//     $token = session('tfc.token');
//     $expiresAt = session('tfc.expires_at');

//     $isLoggedIn = $token
//         && $expiresAt
//         && now()->lt(Carbon::parse($expiresAt));

//     return $isLoggedIn
//         ? redirect()->route('dashboard')
//         : redirect()->route('tfc.login');
// });

Route::redirect('/', '/dashboard');

// TFC API
Route::get('/tfc/login', [TerraceFinanceAuthController::class, 'showLogin'])->name('tfc.login');
Route::post('/tfc/login', [TerraceFinanceAuthController::class, 'login'])->name('tfc.login.submit');
Route::post('/tfc/logout', [TerraceFinanceAuthController::class, 'logout'])->name('tfc.logout');

// Protected Pages
Route::middleware(['tfc.token'])->group(function () {


});

Route::view('/dashboard', 'terrace-finance.dashboard')
    ->name('dashboard');

Route::get('/tfc/leads', [TerraceFinanceLeadController::class, 'index'])
    ->name('tfc.leads.index');
Route::post('/tfc/leads', [TerraceFinanceLeadController::class, 'store'])
    ->name('tfc.leads.store');

Route::get('/tfc/pricing-factor', [TerraceFinancePricingFactorController::class, 'index'])
    ->name('tfc.pricing-factor.index');
Route::post('/tfc/pricing-factor', [TerraceFinancePricingFactorController::class, 'store'])
    ->name('tfc.pricing-factor.store');

Route::get('/tfc/applications', [TerraceFinanceApplicationController::class, 'index'])
    ->name('tfc.applications.index');
Route::post('/tfc/applications', [TerraceFinanceApplicationController::class, 'store'])
    ->name('tfc.applications.store');

Route::get('/tfc/invoices', [TerraceFinanceInvoiceController::class, 'index'])
    ->name('tfc.invoices.index');
Route::post('/tfc/invoices', [TerraceFinanceInvoiceController::class, 'store'])
    ->name('tfc.invoices.store');
Route::get('/tfc/invoices/history', [TerraceFinanceInvoiceController::class, 'history'])
    ->name('tfc.invoices.history');

Route::get('/tfc/offers', [TerraceFinanceOfferController::class, 'index'])
    ->name('tfc.offers.index');
Route::post('/tfc/offers', [TerraceFinanceOfferController::class, 'store'])
    ->name('tfc.offers.store');
Route::get('/tfc/offers/history', [TerraceFinanceOfferController::class, 'history'])
    ->name('tfc.offers.history');

Route::get('/tfc/application-status', [TerraceFinanceApplicationStatusController::class, 'index'])
    ->name('tfc.application-status.index');
Route::post('/tfc/application-status', [TerraceFinanceApplicationStatusController::class, 'store'])
    ->name('tfc.application-status.store');


Route::get('/tfc/status-notifications', [TerraceFinanceStatusNotificationController::class, 'index'])
    ->name('tfc.status-notifications.index');
Route::get('/tfc/status-notifications/history', [TerraceFinanceStatusNotificationController::class, 'history'])
    ->name('tfc.status-notifications.history');
Route::post('/tfc/status-notifications/webhook', [TerraceFinanceStatusNotificationController::class, 'webhook'])
    ->name('tfc.status-notifications.webhook');

Route::get('/tfc/status-notifications', [TerraceFinanceStatusNotificationController::class, 'index'])
    ->name('tfc.status-notifications.index');
Route::post('/tfc/status-notifications/receive', [TerraceFinanceStatusNotificationController::class, 'manualReceive'])
    ->name('tfc.status-notifications.receive');







