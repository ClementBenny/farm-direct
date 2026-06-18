@extends('layouts.public')

@section('title', 'Order Detail — Farm Direct')

@section('content')

@php
    $steps = ['pending', 'confirmed', 'picking', 'packed', 'delivered'];
    $currentIndex = array_search($order->status, $steps);
    $cancelled = $order->status === 'cancelled';
    $progressPercent = ($currentIndex !== false && count($steps) > 1)
        ? ($currentIndex / (count($steps) - 1)) * 100
        : 0;
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
    .detail-header {
        display: flex; align-items: flex-start;
        justify-content: space-between; gap: 16px;
        flex-wrap: wrap; margin-bottom: 8px;
    }
    .detail-ref {
        font-family: 'Cormorant Garamond', serif;
        font-size: 48px; font-weight: 600;
        color: var(--umber); line-height: 1.1;
    }
    .detail-placed {
        font-size: 15px; color: var(--umber);
        opacity: 0.55; margin-top: 6px; letter-spacing: 0.02em;
    }
    .cancel-btn {
        font-size: 12px; letter-spacing: 0.08em; text-transform: uppercase;
        color: #8c2828; background: rgba(140,40,40,0.08);
        border: 1.5px solid rgba(140,40,40,0.25);
        padding: 10px 22px; border-radius: 999px;
        cursor: pointer; font-family: 'Jost', sans-serif; font-weight: 400;
        transition: background 0.2s, border-color 0.2s;
        white-space: nowrap; align-self: center;
    }
    .cancel-btn:hover { background: rgba(140,40,40,0.15); border-color: rgba(140,40,40,0.5); }
    .cancelled-notice-icon {
        width: 48px; height: 48px; border-radius: 50%;
        background: rgba(140,40,40,0.14);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .cancelled-notice-icon i { font-size: 22px; color: #8c2828; }
    .cancelled-notice h4 { font-size: 16px; font-weight: 500; color: #8c2828; margin-bottom: 3px; }
    .cancelled-notice p  { font-size: 14px; color: rgba(140,40,40,0.65); }

    .step-dot { width: 60px; height: 60px; }
    .step-dot i { font-size: 26px; line-height: 1; }
    .step-dot--done i { font-size: 28px; }
    .step-dot--current { box-shadow: 0 0 0 8px rgba(75,54,33,0.12), 0 4px 16px rgba(75,54,33,0.25); }
    .progress-step { gap: 14px; }
    .step-label { font-size: 12px; }
    .progress-track { top: 30px; left: calc(10% + 30px); right: calc(10% + 30px); }

    @media (max-width: 640px) {
        .detail-ref { font-size: 34px; }
        .step-dot   { width: 44px; height: 44px; }
        .step-dot i { font-size: 19px !important; }
        .step-label { font-size: 10px; }
        .progress-track { top: 22px; left: calc(10% + 22px); right: calc(10% + 22px); }
    }
</style>

<div class="page-wrap">

    <a href="{{ route('shop.orders') }}" class="back-link">
        <i class="ph ph-arrow-left"></i> My Orders
    </a>

        <div class="detail-header">
            <div>
                <h1 class="detail-ref">Order #{{ $order->customer_order_number }}</h1>
                <p class="detail-placed">Placed {{ $order->created_at->diffForHumans() }} · {{ $order->created_at->format('d M Y') }}</p>
            </div>
            @if(!$cancelled && $currentIndex !== false && $currentIndex < 3)
                <form action="{{ route('shop.orders.cancel', $order) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to cancel this order?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="cancel-btn">
                        <i class="ph ph-x-circle" style="margin-right:5px;"></i>Cancel Order
                    </button>
                </form>
            @endif
        </div>

    {{-- Status --}}
    <div class="fd-card" style="margin-top:36px;">
        <p class="fd-card-label">Order Status</p>
        @if($cancelled)
            <div class="cancelled-notice" style="gap:16px;padding:20px 24px;border-radius:14px;">
                <div class="cancelled-notice-icon"><i class="ph ph-x-circle"></i></div>
                <div>
                    <h4>Order Cancelled</h4>
                    <p>This order was cancelled and will not be processed.</p>
                </div>
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

    {{-- Items --}}
    <div class="fd-card">
        <p class="fd-card-label">Items Ordered</p>
        <table class="fd-table">
            <thead>
                <tr>
                    <th>Product</th><th>Unit Price</th><th>Qty</th><th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="muted">₹{{ number_format($item->unit_price, 2) }}</td>
                    <td class="muted">{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Order Total</td>
                    <td>₹{{ number_format($order->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Delivery --}}
    <div class="fd-card">
        <p class="fd-card-label">Delivery Details</p>
        <div class="delivery-grid">
            <div class="delivery-field">
                <label>Delivery Address</label>
                <p>{{ $order->delivery_address }}</p>
            </div>
            @if($order->notes)
            <div class="delivery-field">
                <label>Order Notes</label>
                <p>{{ $order->notes }}</p>
            </div>
            @endif
        </div>
    </div>

</div>

@endsection