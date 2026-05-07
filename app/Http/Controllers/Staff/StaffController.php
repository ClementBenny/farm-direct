<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function dashboard()
    {
        $statusCounts = [
            'pending'   => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'picking'   => Order::where('status', 'picking')->count(),
            'packed'    => Order::where('status', 'packed')->count(),
        ];

        $activeOrders = Order::whereIn('status', ['pending', 'confirmed', 'picking', 'packed'])
            ->with(['user', 'items'])
            ->latest()
            ->take(10)
            ->get();

        $lowStockProducts = Product::where('is_active', true)
            ->where('stock', '<=', 10)
            ->orderBy('stock')
            ->get();

        $outForDelivery = Order::where('status', 'delivered')
            ->whereDate('updated_at', today())
            ->with('user')
            ->orderByDesc('updated_at')
            ->take(5)
            ->get();

        $deliveredToday = Order::where('status', 'delivered')
            ->whereDate('updated_at', today())
            ->count();

        return view('staff.dashboard', compact(
            'statusCounts',
            'activeOrders',
            'lowStockProducts',
            'outForDelivery',
            'deliveredToday'
        ));
    }

    public function orders()
    {
        $orders = Order::with('user')
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->orderByRaw("CASE status
                WHEN 'picking'   THEN 1
                WHEN 'confirmed' THEN 2
                WHEN 'pending'   THEN 3
                WHEN 'packed'    THEN 4
                ELSE 5 END")
            ->get();

        return view('staff.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('staff.orders-show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in([
                'confirmed', 'picking', 'packed', 'delivered', 'cancelled'
            ])],
        ]);

        $order->update($validated);

        return redirect()->route('staff.orders.show', $order)
            ->with('success', 'Order marked as ' . ucfirst($validated['status']) . '.');
    }
}