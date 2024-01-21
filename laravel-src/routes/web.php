<?php

use App\Http\Controllers\Business\BusinessChargeController;
use App\Http\Controllers\Business\BusinessController;
use App\Http\Controllers\Business\BusinessCustomerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Setup\SetupController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// アプリケーションとしてのインデックス(ダッシュボードへ)
Route::get('/', fn() => redirect('/dashboard'));

//　認証直後
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 事業所 登録
    Route::name('business.')->prefix('/business')->group(function() {
        // 事業所 登録
        Route::get('/create', [BusinessController::class, 'create'])->name('create');
        // Route::get('/registered', [BusinessController::class, 'create'])->name('registered');
        // 事業所 顧客
        Route::get('/customers', [BusinessCustomerController::class, 'index'])->name('customers');
        Route::get('/customers/create', [BusinessCustomerController::class, 'create'])->name('customers.create');
        // Route::get('/customers/setup/{customer_id}', [BusinessCustomerController::class, 'setupByCheckoutSession'])->name('customers.setup');
        Route::get('/customers/setup/{customer_id}/success', [BusinessCustomerController::class, 'setupSuccessByCheckoutSession'])->name('customers.setup.success');
        Route::get('/customers/setup/{customer_id}', [BusinessCustomerController::class, 'setup'])->name('customers.setup');
        Route::get('/customers/payment/methods/{customer_id}', [BusinessChargeController::class, 'index'])->name('customers.payment.methods');
        Route::get('/customers/{customer_id}/charge/{payment_method_id}', [BusinessChargeController::class, 'charge'])->name('customers.charge');
        Route::get('/customers/{customer_id}/charge/{payment_method_id}/success', [BusinessChargeController::class, 'chargeSuccess'])->name('customers.charge.success');
    });

    // 将来支払い
    Route::name('setup.')->prefix('/setup')->group(function() {
        // 事業所 登録
        Route::get('/create', [SetupController::class, 'create'])->name('create');
        // Route::get('/registered', [SetupController::class, 'create'])->name('registered');
    });
});

Route::get('/customers', [BusinessController::class, 'test']);
Route::get('/test', [BusinessCustomerController::class, 'test']);
Route::get('/stripe/auth/callback', [BusinessController::class, 'registered']);
// setupintent return_urlフロントエンド (CSRFトークン外)
// Route::get('/business/customers/setup/{customer_id}/success', [BusinessCustomerController::class, 'setupSuccess'])->name('business.customers.setup.success');

require __DIR__.'/auth.php';
