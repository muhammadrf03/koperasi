<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Barang (per kategori)
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/export', [BarangController::class, 'export'])->name('barang.export');
    Route::get('/barang/{category}', [BarangController::class, 'index'])->name('barang.show');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::put('/barang/{item}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{item}', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::post('/barang/{item}/restore', [BarangController::class, 'restore'])->name('barang.restore');

    // Transaksi In/Out
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi', [TransactionController::class, 'store'])->name('transaksi.store');
    Route::delete('/transaksi/{transaction}', [TransactionController::class, 'destroy'])->name('transaksi.destroy');

    // Superadmin Area
    Route::middleware(['role:superadmin'])->prefix('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    });
});

require __DIR__.'/auth.php';





