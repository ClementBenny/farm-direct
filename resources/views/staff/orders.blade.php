@extends('layouts.staff')

@section('page-title', 'Orders')

@section('content')
@php
$statusColours = [
    'pending'   => 's-badge-pending',
    'confirmed' => 's-badge-confirmed',
    'picking'   => 's-badge-picking',
    'packed'    => 's-badge-packed',
    'delivered' => 's-badge-delivered',
    'cancelled' => 's-badge-cancelled',
];
@endphp

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:20px;font-weight:700;color:var(--umber);margin:0;">Active Orders</h1>
    <span style="font-size:13px;color:var(--mauve);">Orders currently being processed — sorted by urgency</span>
</div>

<div class="s-card">
    <div class="s-card-head">
        <span class="s-card-title">
            <i class="ti ti-clipboard-list" aria-hidden="true" style="font-size:16px;vertical-align:-2px;margin-right:6px;"></i>
            All active orders
        </span>
        <span style="font-size:12px;color:var(--mauve);">{{ $orders->count() }} orders</span>
    </div>

    @forelse($orders as $order)
    <a href="{{ route('staff.orders.show', $order) }}" class="s-row" style="text-decoration:none;display:flex;">
        <div style="flex:1;min-width:0;">
            <div class="s-row-ref">
                #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                <span style="color:var(--mauve);font-weight:400;font-size:13px;">· {{ $order->user->name }}</span>
                <span style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;
                      color:{{ $order->user->role === 'shop' ? 'var(--olive)' : 'var(--mauve)' }};
                      background:{{ $order->user->role === 'shop' ? 'rgba(128,128,0,0.1)' : 'rgba(196,164,132,0.15)' }};
                      padding:2px 7px;border-radius:4px;margin-left:6px;">
                    {{ $order->user->role === 'shop' ? 'Wholesale' : 'Retail' }}
                </span>
            </div>
            <div class="s-row-meta" style="margin-top:3px;">
                <i class="ti ti-map-pin" aria-hidden="true" style="font-size:12px;vertical-align:-1px;margin-right:3px;"></i>
                {{ Str::limit($order->delivery_address, 50) }}
                · {{ $order->created_at->diffForHumans() }}
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
            <span class="s-badge {{ $statusColours[$order->status] }}">{{ ucfirst($order->status) }}</span>
            <i class="ti ti-chevron-right" aria-hidden="true" style="color:var(--mauve);font-size:16px;"></i>
        </div>
    </a>
    @empty
    <div class="s-empty">
        <i class="ti ti-circle-check" aria-hidden="true" style="font-size:32px;color:var(--mauve);display:block;margin-bottom:8px;"></i>
        No active orders right now.
    </div>
    @endforelse
</div>
@endsection