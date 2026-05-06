<?php

namespace App\Http\Controllers\Wholesale;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->with('category')
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();

        $categories = Category::withCount(['products' => function ($q) {
            $q->where('is_active', true);
        }])->get()->filter(fn($c) => $c->products_count > 0);

        return view('wholesale.index', compact('products', 'categories'));
    }

    public function category(Category $category)
    {
        $products = Product::with('category')
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->with('category')
            ->orderBy('name')
            ->get();

        $categories = Category::withCount(['products' => function ($q) {
            $q->where('is_active', true);
        }])->get()->filter(fn($c) => $c->products_count > 0);

        return view('wholesale.index', compact('products', 'categories', 'category'));
    }
}