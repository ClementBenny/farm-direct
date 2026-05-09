@extends('layouts.staff')

@section('page-title', 'Dashboard')

@section('content')
@php
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'morning' : ($hour < 17 ? 'afternoon' : 'evening');
    $statColours = [
        'pending'   => '#B45309',
        'confirmed' => '#1D4ED8',
        'picking'   => '#6D28D9',
        'packed'    => '#4338CA',
    ];
@endphp

<style>
    /* Hover Effects for Stats */
    .stat {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: default;
    }
    .stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(75, 54, 33, 0.1);
        border-color: var(--mauve);
    }

    /* Hover Effects for Cards */
    .s-card {
        transition: box-shadow 0.3s ease;
    }
    .s-card:hover {
        box-shadow: 0 6px 16px rgba(75, 54, 33, 0.08);
    }

    /* Row Hover Highlights */
    .s-row {
        transition: background 0.2s ease;
        text-decoration: none;
        color: inherit;
    }
    .s-row:hover {
        background: rgba(247, 231, 206, 0.4);
    }

    /* Fix for the low stock grid hover */
    .s-low-item {
        transition: background 0.2s ease;
    }
    .s-low-item:hover {
        background: rgba(247, 231, 206, 0.4);
    }
</style>

{{-- Greeting --}}
<div style="margin-bottom:2rem;">
    <h1 style="font-size:24px; font-weight:700; color:var(--umber); margin:0;">
        Good {{ $greeting }}, {{ explode(' ', auth()->user()->name)[0] }}
    </h1>
    <span style="font-size:15px; color:var(--mauve); font-weight: 500;">
        {{ now()->format('l, j F Y') }}
    </span>
</div>

{{-- Stat cards --}}
<div class="stat-grid">
    @foreach(['pending' => 'Pending', 'confirmed' => 'Confirmed', 'picking' => 'Picking', 'packed' => 'Packed'] as $status => $label)
    <div class="stat">
        <div class="stat-label">{{ $label }}</div>
        <div class="stat-num" style="color:{{ $statColours[$status] }};">{{ $statusCounts[$status] ?? 0 }}</div>
    </div>
    @endforeach
    <div class="stat">
        <div class="stat-label">Delivered Today</div>
        <div class="stat-num" style="color:var(--olive);">{{ $deliveredToday }}</div>
    </div>
</div>

{{-- Active orders --}}
<div class="s-card">
    <div class="s-card-head">
        <span class="s-card-title">
            <i class="ti ti-clipboard-list" style="font-size:18px; vertical-align:middle; margin-right:8px;"></i>
            Active Orders
        </span>
        <a href="{{ route('staff.orders') }}" class="s-card-link">View all orders →</a>
    </div>
    @forelse($activeOrders as $order)
    <a href="{{ route('staff.orders.show', $order) }}" class="s-row" style="display:flex;">
        <div>
            <div class="s-row-ref">
                #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                <span style="color:var(--mauve); font-weight:400; font-size:14px;">· {{ $order->user->name }}</span>
            </div>
            <div class="s-row-meta">
                <span style="text-transform: uppercase; font-weight: 600; font-size: 11px;">{{ $order->user->role === 'shop' ? 'Wholesale' : 'Retail' }}</span>
                · {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}
                · ₹{{ number_format($order->total, 2) }}
                · {{ $order->created_at->diffForHumans() }}
            </div>
        </div>
        <div style="display:flex; align-items:center; gap:12px;">
            <span class="s-badge s-badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
            <i class="ti ti-chevron-right" style="color:var(--mauve)"></i>
        </div>
    </a>
    @empty
    <div class="s-empty">All caught up! No active orders to process.</div>
    @endforelse
</div>

{{-- Bottom two columns --}}
<div class="s-two-col">

    {{-- Low stock --}}
    <div class="s-card">
        <div class="s-card-head">
            <span class="s-card-title">
                <i class="ti ti-alert-triangle" style="font-size:18px; vertical-align:middle; margin-right:8px; color:#B45309;"></i>
                Stock Alerts
            </span>
            <a href="{{ route('staff.stock') }}" class="s-card-link">Manage →</a>
        </div>
        <div class="s-low-grid">
            @forelse($lowStockProducts as $product)
            <div class="s-low-item">
                <div>
                    <div class="s-low-name">{{ $product->name }}</div>
                    <div class="s-low-sub">
                        {{ $product->stock <= 0 ? 'Out of stock' : $product->stock . ' ' . ($product->unit ?? 'units') . ' remaining' }}
                    </div>
                </div>
                @if($product->stock <= 0)
                    <span class="s-stock-pill" style="background:#FEE2E2; color:#991B1B;">Critical</span>
                @else
                    <span class="s-stock-pill" style="background:#FEF3C7; color:#92400E;">Low</span>
                @endif
            </div>
            @empty
            <div class="s-empty" style="grid-column:1/-1;">All stock levels are healthy.</div>
            @endforelse
        </div>
    </div>

    {{-- Out for delivery / Recently Deliveries --}}
    <div class="s-card">
        <div class="s-card-head">
            <span class="s-card-title">
                <i class="ti ti-truck-delivery" style="font-size:18px; vertical-align:middle; margin-right:8px;"></i>
                Recent Deliveries
            </span>
        </div>
        <div style="min-height: 200px;">
            @forelse($outForDelivery as $order)
            <div class="s-row">
                <div>
                    <div class="s-row-ref">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
                    <div class="s-row-meta">{{ $order->user->name }} · {{ $order->updated_at->format('h:i A') }}</div>
                </div>
                <span class="s-badge s-badge-delivered">Completed</span>
            </div>
            @empty
            <div class="s-empty">No orders have been marked delivered today.</div>
            @endforelse
        </div>
        <div class="s-footer-note" style="background: var(--ivory); padding: 12px 20px;">
            <span>Daily Performance</span>
            <strong>{{ $deliveredToday }} Orders Delivered Today</strong>
        </div>
    </div>

</div>
@endsection