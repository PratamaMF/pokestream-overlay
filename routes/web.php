<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

});

Route::get('/realtime/queue-fullscreen', function() { return view('orders.realtime_queue'); })->name('realtime.queueFull');
Route::get('/realtime/product-fullscreen', function() { return view('orders.realtime_product'); })->name('realtime.productFull');

Route::get('/api/live-stream-data-snapshot', [DashboardController::class, 'getLiveSnapshot']);


Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/pos', [OrderController::class, 'posIndex'])->name('pos.index');
    Route::post('/pos', [OrderController::class, 'posStore'])->name('pos.store');

    Route::get('/orders-history', [OrderController::class, 'historyIndex'])->name('orders.history');
    Route::put('/orders-history/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/orders-history/export-pdf', [OrderController::class, 'exportPDF'])->name('orders.exportPdf');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('notes', NoteController::class);
    Route::get('/realtime/note-fullscreen/{id}', [NoteController::class, 'showOverlay'])->name('notes.overlay');
    
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('/orders', function() { return "Halaman Order"; })->name('orders.index');
    Route::get('/transactions', function() { return "Halaman Transaksi"; })->name('transactions.index');
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity.index');
    Route::delete('/activity-log/clear', [ActivityLogController::class, 'clearHistory'])->name('activity.clear');
});
