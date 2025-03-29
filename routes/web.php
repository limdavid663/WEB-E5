<?php

use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $products = Product::all();
    return Inertia::render('public/Welcome', [
        'products' => $products
    ]);
})->name('home');

Route::get('admin/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'super_admin'])->name('dashboard');

Route::middleware(['auth', 'super_admin'])->prefix('admin')->group(function () {
    Route::resource('products', ProductController::class);
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
