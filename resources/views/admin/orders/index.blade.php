@extends('layouts.admin')

@section('page-title', 'Orders')

@section('content')

<div class="a-page-head">
    <div>
        <h1 class="a-page-title">Orders</h1>
        <p class="a-page-sub">{{ $orders->count() }} total orders</p>
    </div>
</div>

<div class="a-card" style="margin-bottom:1.25rem">
    <div class="a-card-body">
        <form method="GET" action="{{ route('admin.orders.index') }}" style="display:flex; gap:1rem; align-items:flex-end; flex-wrap:wrap">
            <div style="display:flex; flex-direction:column; gap:0.35rem; flex:1; min-width:180px">
                <label class="a-label">Customer</label>
                <select name="user_id" class="a-input">
                    <option value="">All Customers</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; flex-direction:column; gap:0.35rem; flex:1; min-width:160px">
                <label class="a-label">Status</label>
                <select name="status" class="a-input">
                    <option value="">All Statuses</option>
                    @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; gap:0.5rem">
                <button type="submit" class="a-btn a-btn-primary">Filter</button>
                <a href="{{ route('admin.orders.index') }}" class="a-btn a-btn-ghost">Reset</a>
            </div>
        </form>
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
                            No orders found.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection