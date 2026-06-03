@extends('layouts.admin')

@section('page-title')Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}@endsection

@section('content')

<div class="a-page-head">
    <div>
        <a href="{{ route('admin.orders.index') }}"
           style="font-size:12px; color:var(--muted); text-decoration:none; display:inline-flex; align-items:center; gap:4px; margin-bottom:6px">
            <i class="ti ti-arrow-left"></i> Back to Orders
        </a>
        <div style="display:flex; align-items:center; gap:10px">
            <h1 class="a-page-title">Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</h1>
            <span class="a-badge a-badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
        </div>
        <p class="a-page-sub">Placed {{ $order->created_at->diffForHumans() }}</p>
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 320px; gap:1.25rem; align-items:start">

    {{-- Order Items --}}
    <div class="a-card">
        <div class="a-card-head">
            <span class="a-card-title"><i class="ti ti-list"></i> Items</span>
        </div>
        <div class="a-card-body" style="padding:0">
            <table class="a-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="right">Unit Price</th>
                        <th class="right">Qty</th>
                        <th class="right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td style="font-weight:600">{{ $item->product->name }}</td>
                        <td class="right" style="color:var(--muted)">₹{{ number_format($item->unit_price, 2) }}</td>
                        <td class="right" style="color:var(--muted)">{{ $item->quantity }}</td>
                        <td class="right" style="font-weight:600">₹{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right; font-weight:700; color:var(--dark)">Total</td>
                        <td style="text-align:right; font-weight:800; color:var(--dark)">₹{{ number_format($order->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Sidebar --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem">

        {{-- Customer --}}
        <div class="a-card">
            <div class="a-card-head">
                <span class="a-card-title"><i class="ti ti-user"></i> Customer</span>
            </div>
            <div class="a-card-body">
                <p style="font-weight:600; color:var(--dark); margin:0">{{ $order->user->name }}</p>
                <p style="font-size:12px; color:var(--muted); margin:2px 0 0">{{ $order->user->email }}</p>

                <hr class="a-divider">

                <p class="a-label">Delivery Address</p>
                <p style="font-size:13px; color:var(--dark); margin:0">{{ $order->delivery_address }}</p>

                @if($order->notes)
                    <hr class="a-divider">
                    <p class="a-label">Notes</p>
                    <p style="font-size:13px; color:var(--dark); margin:0">{{ $order->notes }}</p>
                @endif
            </div>
        </div>

        {{-- Update Status --}}
        <div class="a-card">
            <div class="a-card-head">
                <span class="a-card-title"><i class="ti ti-refresh"></i> Update Status</span>
            </div>
            <div class="a-card-body">
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="a-form-group">
                        <label class="a-label">Status</label>
                        <select name="status" class="a-input">
                            @foreach(['pending', 'confirmed', 'picking', 'packed', 'delivered', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="a-btn a-btn-primary" style="width:100%; justify-content:center">
                        <i class="ti ti-device-floppy"></i> Save Status
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection