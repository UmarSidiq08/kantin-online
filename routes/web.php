<?php

use App\Http\Middleware\EnsureCanteenOwner;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserOrderController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\LogOrderController;
use App\Http\Controllers\Admin\LaporanPenjualanController;
use App\Http\Controllers\Admin\PremiumController;



Route::get('/', function () {
    return view('auth.login');
});
require __DIR__ . '/auth.php';
Route::post('/premium/callback', [PremiumController::class, 'callback']);
Route::middleware('auth')->group(function () {

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::middleware(['role:admin', 'can:canteen.owner'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/riwayat-pesanan', [OrderController::class, 'logHistory'])->name('logs');
        Route::get('/riwayat-pesanan/data', [LogOrderController::class, 'data'])->name('logs.data');

        Route::prefix('orders')->name('orders.')->controller(OrderController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/{order}/accept', 'markAsProcessed')->name('mark-processed');
            Route::post('/{order}/mark-processed-cash',  'markProcessedCash')->name('mark-processed-cash');
            Route::post('/{order}/confirm-cash-payment', 'confirmCashPayment')->name('confirm-cash-payment');

            Route::post('/{order}/complete', 'markAsCompleted')->name('complete-and-delete');
            Route::post('/{order}/reject', 'markAsRejected')->name('reject');
            Route::post('/rejected-delete/{order}', 'deleteRejected')->name('rejected-delete');
        });
        Route::prefix('menus')->name('menu.')->controller(MenuController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/table', 'table')->name('table');
            Route::post('/store', 'store')->name('store');
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'destroy')->name('destroy');
        });
        Route::prefix('laporan-penjualan')->name('laporan.')->controller(LaporanPenjualanController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/data', 'data')->name('data');
            Route::get('/admin/laporan/chart', 'chartData')->name('chart');
            Route::get('/export/excel', 'exportExcel')->name('export.excel');
            Route::get('/export/pdf', 'exportPDF')->name('export.pdf');
        });
        Route::prefix('premium')->name('premium.')->controller(PremiumController::class)->group(function () {
            Route::get('/', 'show')->name('show');
            Route::post('/', 'pay')->name('pay');
            Route::post('/premium/callback', 'callback');

        });
    });
    Route::middleware('role:user')->prefix('user')->name('user.')->group(function () {

        Route::controller(UserController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('dashboard');
            Route::get('/menu', 'index')->name('menu.index');
            Route::get('/pilih-kantin/{id}', 'pilihKantin')->name('pilih-kantin');
        });
        Route::controller(PaymentController::class)->group(function () {
            Route::get('/payment/success', 'success')->name('payment.success');
            Route::get('/payment/pending', 'success')->name('payment.pending');
            Route::post('/checkout', 'checkout')->name('checkout');
            Route::post('/checkout/cash', 'checkoutCash')->name('checkout.cash');
        });

        Route::prefix('orders')->name('orders.')->controller(UserOrderController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/success', 'success')->name('success');
            Route::get('/history', 'history')->name('history');
            Route::get('/history/table', 'table')->name('history.table');
        });

        Route::prefix('keranjang')->name('cart.')->controller(CartController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/tambah', 'add')->name('add');
            Route::delete('/hapus/{id}', 'destroy')->name('destroy');
        });
    });
});
