<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();

        return Inertia::render('categories/Index', [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $allProducts = Product::select('id', 'name', 'price', 'image')->get();

        return Inertia::render('categories/Create', [
            'allProducts' => $allProducts
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'product_ids' => 'present|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $category = Category::create([
            'name' => $validated['name']
        ]);

        // Always sync products (empty array will clear all associations)
        $category->products()->sync($validated['product_ids']);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        // Load related products for the form
        $category->load('products');

        // Get all products for the dropdown
        $allProducts = Product::select('id', 'name', 'price', 'image')->get();

        return Inertia::render('categories/Edit', [
            'category' => $category,
            'allProducts' => $allProducts
        ]);
    }


    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'product_ids' => 'present|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $category->update([
            'name' => $validated['name']
        ]);

        // Always sync products (empty array will clear all associations)
        $category->products()->sync($validated['product_ids']);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        // Detach all products first (not strictly necessary with cascading deletes in migration,
        // but included for clarity)
        $category->products()->detach();

        // Delete the category
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
