<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function categories(Request $request)
    {
        [$sort, $direction] = $this->resolveSort($request, ['name', 'products_count', 'created_at'], 'name', 'asc');

        $rows = Category::withCount('products')->orderBy($sort, $direction)->get();

        return view('admin.reports.categories', compact('rows', 'sort', 'direction'));
    }

    public function products(Request $request)
    {
        [$sort, $direction] = $this->resolveSort($request, ['name', 'price', 'stock', 'created_at'], 'name', 'asc');

        $rows = Product::with('category')
            ->when($request->filled('category_id'), fn($q) => $q->where('category_id', $request->category_id))
            ->orderBy($sort, $direction)
            ->get();

        $categories = Category::orderBy('name')->get();

        return view('admin.reports.products', compact('rows', 'sort', 'direction', 'categories'));
    }

    public function orders(Request $request)
    {
        [$sort, $direction] = $this->resolveSort($request, ['created_at', 'status', 'total'], 'created_at', 'desc');

        $query = Order::with('user');

        // Date-wise: explicit range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Month-wise: month dropdown always pairs with a year (defaults to current year
        // if the person picks a month but leaves year on "All years")
        if ($request->filled('month')) {
            $year = $request->filled('year') ? $request->year : now()->year;
            $query->whereRaw("strftime('%Y-%m', created_at) = ?", [sprintf('%04d-%02d', $year, $request->month)]);
        } elseif ($request->filled('year')) {
            // Year picked alone, no month — whole-year report
            $query->whereRaw("strftime('%Y', created_at) = ?", [$request->year]);
        }

        // Customer-wise
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Status-wise
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rows = $query->orderBy($sort, $direction)->get();

        $customers = User::whereIn('role', ['customer', 'shop'])->orderBy('name')->get();
        $years = $this->availableOrderYears();

        return view('admin.reports.orders', compact('rows', 'sort', 'direction', 'customers', 'years'));
    }
    
    public function users(Request $request)
    {
        [$sort, $direction] = $this->resolveSort($request, ['name', 'email', 'role', 'created_at'], 'name', 'asc');

        $rows = User::when($request->filled('role'), fn($q) => $q->where('role', $request->role))
            ->orderBy($sort, $direction)
            ->get();

        return view('admin.reports.users', compact('rows', 'sort', 'direction'));
    }


    private function resolveSort(Request $request, array $allowed, string $default, string $defaultDirection): array
    {
        $sort = $request->get('sort', $default);
        if (!in_array($sort, $allowed)) {
            $sort = $default;
        }

        $direction = $request->get('direction', $defaultDirection);
        $direction = $direction === 'asc' ? 'asc' : 'desc';

        return [$sort, $direction];
    }

    private function availableOrderYears()
    {
        $years = Order::selectRaw("DISTINCT strftime('%Y', created_at) as y")
            ->pluck('y')
            ->filter()
            ->sortDesc()
            ->values();

        return $years->isEmpty() ? collect([now()->year]) : $years;
    }
}