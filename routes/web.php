<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';


t()->get('/test', function () {
    return 'test';
});

require __DIR__.'/auth.php';

use App\Http\Controllers\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\SettingsController;

Route::middleware('auth')->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/minuman', [AdminController::class, 'index'])->name('minuman.index');
});

use App\Http\Controllers\MinumanController;

Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/minuman/create', [MinumanController::class, 'create'])->name('minuman.create');
    Route::post('/minuman', [MinumanController::class, 'store'])->name('minuman.store');
    Route::get('/minuman/{id}/edit', [MinumanController::class, 'edit'])->name('minuman.edit');

    Route::put('/minuman/{id}', [MinumanController::class, 'update'])->name('minuman.update');
    Route::delete('/minuman/{id}', [MinumanController::class, 'destroy'])->name('minuman.destroy');
});

use App\Http\Controllers\OrderController;
Route::middleware('auth')->group(function () {
    Route::post('/order/{minumanId}', [OrderController::class, 'placeOrder'])->name('order.place');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
}); 

use App\Http\Controllers\OrderController;
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin/orders', [OrderController::class, 'adminIndex'])->name('admin.orders.index');
    Route::post('/admin/orders/{orderId}/update-status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
});