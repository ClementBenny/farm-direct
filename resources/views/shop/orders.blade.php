@extends('layouts.public')

@section('title', 'My Orders — Farm Direct')

@section('content')

@php
    $stepLabels = [
        'pending'   => 'Order Placed',
        'confirmed' => 'Confirmed',
        'picking'   => 'Being Picked',
        'packed'    => 'Packed',
        'delivered' => 'Delivered',
    ];
    $stepIcons = [
        'pending'   => 'ph-receipt',
        'confirmed' => 'ph-seal-check',
        'picking'   => 'ph-basket',
        'packed'    => 'ph-package',
        'delivered' => 'ph-truck',
    ];
@endphp

<style>
    .order-card-top {
        display: flex; align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 28px; gap: 16px; flex-wrap: wrap;
    }
    .order-ref {
        font-family: 'Cormorant Garamond', serif;
        font-size: 28px; font-weight: 600;
        color: var(--umber); letter-spacing: 0.02em;
    }
    .order-meta {
        font-size: 15px; color: var(--umber);
        margin-top: 5px; letter-spacing: 0.02em; opacity: 0.65;
    }
    .order-card-actions { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }

    /* 44px dots for index page */
    .step-dot { width: 44px; height: 44px; }
    .step-dot i { font-size: 20px; line-height: 1; }
    .step-dot--done i  { font-size: 22px; }
    .step-dot--current { box-shadow: 0 0 0 7px rgba(75,54,33,0.12), 0 4px 14px rgba(75,54,33,0.25); }
    .progress-step { gap: 10px; }
    .step-label { font-size: 12px; }
    .progress-track { top: 22px; left: calc(10% + 22px); right: calc(10% + 22px); }

    @media (max-width: 640px) {
        .step-dot   { width: 34px; height: 34px; }
        .step-dot i { font-size: 15px !important; }
        .step-label { font-size: 10px; }
        .progress-track { top: 17px; left: calc(10% + 17px); right: calc(10% + 17px); }
    }
</style>

<div class="page-wrap">

    <p class="section-label">Account</p>
    <h1 class="page-heading">Your Orders</h1>
    <p class="page-sub">{{ $orders->count() }} {{ Str::plural('order', $orders->count()) }} placed</p>

    @forelse($orders as $order)
        @php
            $steps = ['pending', 'confirmed', 'picking', 'packed', 'delivered'];
            $currentIndex = array_search($order->status, $steps);
            $progressPercent = ($currentIndex !== false && count($steps) > 1)
                ? ($currentIndex / (count($steps) - 1)) * 100
                : 0;
        @endphp

        <div class="fd-card">
            <div class="order-card-top">
                <div>
                    <p class="order-ref">#{{ strtoupper(substr(md5($order->id . $order->created_at), 0, 8)) }}</p>
                    <p class="order-meta">
                        {{ $order->created_at->format('d M Y') }} &nbsp;·&nbsp; ₹{{ number_format($order->total, 2) }}
                    </p>
                </div>
                <div class="order-card-actions">
                    <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    <a href="{{ route('shop.orders.show', $order) }}" class="fd-link">View details →</a>
                </div>
            </div>

            <div class="fd-divider"></div>

            @if($order->status === 'cancelled')
                <div class="cancelled-notice">
                    <i class="ph ph-x-circle"></i>
                    <span>This order was cancelled and will not be processed.</span>
                </div>
            @else
                <div class="progress-wrap">
                    <div class="progress-track">
                        <div class="progress-fill" style="width: {{ $progressPercent }}%"></div>
                    </div>
                    @foreach($steps as $i => $step)
                        @php
                            $isDone    = $currentIndex !== false && $i < $currentIndex;
                            $isCurrent = $currentIndex !== false && $i === $currentIndex;
                            $dotClass   = $isDone    ? 'step-dot--done'    : ($isCurrent ? 'step-dot--current' : 'step-dot--future');
                            $labelClass = $isDone    ? 'step-label--done'  : ($isCurrent ? 'step-label--current' : 'step-label--future');
                        @endphp
                        <div class="progress-step">
                            <div class="step-dot {{ $dotClass }}">
                                <i class="ph-fill ph-check-circle"></i>
                                <i class="ph-light {{ $stepIcons[$step] }} step-ph"></i>
                            </div>
                            <p class="step-label {{ $labelClass }}">{{ $stepLabels[$step] }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @empty
        <div class="empty-state">
            <div class="empty-state-icon"><i class="ph-light ph-receipt"></i></div>
            <h3>No orders yet</h3>
            <p>When you place an order, it will appear here.</p>
            <a href="{{ route('shop.index') }}" class="btn-primary">Browse the shop</a>
        </div>
    @endforelse

</div>

@endsection