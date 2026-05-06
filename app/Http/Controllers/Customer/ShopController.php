<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class ShopController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        $products = Product::where('is_active', true)
            ->with('category')         
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();

        return view('shop.index', compact('categories', 'products'));
    }

    public function category(Category $category)
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        $products   = Product::where('is_active', true)
                             ->with('category')
                             ->where('category_id', $category->id)
                             ->orderBy('name')
                             ->get();

        return view('shop.index', compact('categories', 'products', 'category'));
    }
}