@extends('layouts.admin')

@section('page-title', 'Orders')

@section('content')

<div class="a-page-head">
    <div>
        <h1 class="a-page-title">Orders</h1>
        <p class="a-page-sub">{{ $orders->count() }} total orders</p>
    </div>
</div>

<div class="a-card">
    <div class="a-card-body" style="padding:0">
        <table class="a-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th class="right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td style="font-family:monospace; font-size:12px; color:var(--muted)">
                        #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td>
                        <p style="font-weight:600; color:var(--dark); margin:0">{{ $order->user->name }}</p>
                        <p style="font-size:11px; color:var(--muted); margin:2px 0 0">{{ $order->user->email }}</p>
                    </td>
                    <td>
                        <span class="a-badge a-badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td style="font-weight:600">₹{{ number_format($order->total, 2) }}</td>
                    <td style="color:var(--muted)">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="right">
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="a-btn a-btn-ghost" style="font-size:0.8rem; padding:0.3rem 0.75rem">
                            <i class="ti ti-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="a-empty">
                            <i class="ti ti-shopping-cart-off" style="font-size:2rem; margin-bottom:0.5rem; display:block"></i>
                            No orders yet.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection