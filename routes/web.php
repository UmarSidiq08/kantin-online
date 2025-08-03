<?php

use App\Http\Middleware\EnsureCanteenOwner;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserOrderController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\LogOrderController;



Route::get('/', function () {
    return view('auth.login');
});
require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {


    Route::middleware(['role:admin', 'can:canteen.owner'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/riwayat-pesanan', [OrderController::class, 'logHistory'])->name('logs');
        Route::get('/riwayat-pesanan/data', [LogOrderController::class, 'data'])->name('logs.data');


        Route::prefix('orders')->name('orders.')->controller(OrderController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/{order}/accept', 'markAsProcessed')->name('mark-processed');
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
    });
    Route::middleware('role:user')->prefix('user')->name('user.')->group(function () {

        Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
        Route::get('/menu', [UserController::class, 'index'])->name('menu.index');
        Route::get('/pilih-kantin/{id}', [UserController::class, 'pilihKantin'])->name('pilih-kantin');
        Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
        Route::get('/payment/pending', [PaymentController::class, 'success'])->name('payment.pending');
        Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout');

        Route::prefix('orders')->name('orders.')->controller(UserOrderController::class)->group(function () {
            Route::get('/', 'index')->name('index');

            Route::get('/success', 'success')->name('success');
            Route::get('/history', 'history')->name('history');
        });


        Route::prefix('keranjang')->name('cart.')->controller(CartController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/tambah', 'add')->name('add');
            Route::delete('/hapus/{id}', 'destroy')->name('destroy');
        });
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
