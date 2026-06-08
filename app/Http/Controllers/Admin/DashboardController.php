<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        
        $totalUsers = User::whereIn('role', ['customer', 'shop'])->count();
        $totalProducts = Product::count();
        $totalOrders   = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');

        $usersByRole = User::selectRaw('role, count(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        $recentUsers = User::orderByDesc('created_at')->limit(5)->get();

        $salesData = DB::table('orders')
            ->selectRaw("strftime('%Y-%W', created_at) as week, strftime('%m', created_at) as month_num, strftime('%d', created_at) as day, COUNT(*) as order_count, SUM(total) as revenue")
            ->where('status', '!=', 'cancelled')
            ->where('created_at', '>=', now()->subWeeks(11))
            ->groupByRaw("strftime('%Y-%W', created_at)")
            ->orderBy('week')
            ->get();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'usersByRole',
            'recentUsers',
            'salesData',
        ));
    }
}