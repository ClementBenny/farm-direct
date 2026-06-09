<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $users = User::whereIn('role', ['customer', 'shop'])->orderBy('name')->get();

        $orders = Order::with('user')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->status,  fn($q) => $q->where('status', $request->status))
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders', 'users'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in([
                'pending', 'confirmed', 'picking', 'packed', 'delivered', 'cancelled'
            ])],
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order status updated.');
    }
}