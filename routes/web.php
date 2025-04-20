<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    // Start with a base query for products with their categories
    $productsQuery = Product::with('categories');

    // If a category is specified, filter products by that category
    if ($request->has('category')) {
        $categoryId = $request->category;
        $productsQuery->whereHas('categories', function($query) use ($categoryId) {
            $query->where('categories.id', $categoryId);
        });
    }

    // Get all products based on the query
    $products = $productsQuery->get();

    // Get all categories for the navigation
    $categories = Category::all();

    // Get the current category object if one is selected
    $currentCategory = $request->has('category') ?
        Category::find($request->category) : null;

    return Inertia::render('public/Welcome', [
        'products' => $products,
        'categories' => $categories,
        'currentCategory' => $currentCategory
    ]);
})->name('home');


Route::middleware(['auth', 'super_admin'])->prefix('admin')->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
