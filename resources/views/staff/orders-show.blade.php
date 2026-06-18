@extends('layouts.staff')

@section('page-title')Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}@endsection

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
$nextStatus = [
    'pending'   => 'confirmed',
    'confirmed' => 'picking',
    'picking'   => 'packed',
    'packed'    => 'delivered',
];
$nextLabels = [
    'pending'   => 'Confirm order',
    'confirmed' => 'Start picking',
    'picking'   => 'Mark as packed',
    'packed'    => 'Mark as delivered',
];
@endphp




{{-- Page header --}}
<div style="margin-bottom:1.5rem;">
    <a href="{{ route('staff.orders') }}" style="font-size:13px;color:var(--olive);text-decoration:none;display:inline-flex;align-items:center;gap:4px;margin-bottom:10px;">
        <i class="ti ti-arrow-left" aria-hidden="true" style="font-size:14px;"></i> Back to orders
    </a>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        <h1 style="font-size:22px;font-weight:700;color:var(--umber);margin:0;">
            Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
        </h1>
        <span class="s-badge {{ $statusColours[$order->status] }}">{{ ucfirst($order->status) }}</span>
        <span style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;
              color:{{ $order->user->role === 'shop' ? 'var(--olive)' : 'var(--mauve)' }};
              background:{{ $order->user->role === 'shop' ? 'rgba(128,128,0,0.1)' : 'rgba(196,164,132,0.15)' }};
              padding:3px 8px;border-radius:4px;">
            {{ $order->user->role === 'shop' ? 'Wholesale' : 'Retail' }}
        </span>
    </div>
    <p style="font-size:13px;color:var(--mauve);margin:4px 0 0;">
        {{ $order->user->name }} · Placed {{ $order->created_at->diffForHumans() }}
    </p>
</div>

<div class="s-two-col" style="align-items:start;">

    {{-- Pick list --}}
    <div class="s-card" style="margin-bottom:0;">
        <div class="s-card-head">
            <span class="s-card-title">
                <i class="ti ti-list-check" aria-hidden="true" style="font-size:16px;vertical-align:-2px;margin-right:6px;"></i>
                Pick list
            </span>
            <span style="font-size:12px;color:var(--mauve);">{{ $order->items->count() }} items</span>
        </div>
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
            <thead>
                <tr style="background:var(--ivory);">
                    <th style="text-align:left;padding:10px 18px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--mauve);border-bottom:1px solid rgba(196,164,132,0.2);">Product</th>
                    <th style="text-align:left;padding:10px 18px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--mauve);border-bottom:1px solid rgba(196,164,132,0.2);">Category</th>
                    <th style="text-align:right;padding:10px 18px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--mauve);border-bottom:1px solid rgba(196,164,132,0.2);">Qty</th>
                    <th style="text-align:right;padding:10px 18px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--mauve);border-bottom:1px solid rgba(196,164,132,0.2);">Unit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr style="{{ $order->status === 'picking' ? 'background:rgba(247,231,206,0.2);' : '' }}border-bottom:1px solid rgba(196,164,132,0.1);">
                    <td style="padding:12px 18px;font-weight:600;color:var(--umber);">{{ $item->product->name }}</td>
                    <td style="padding:12px 18px;color:var(--mauve);font-size:12px;">{{ $item->product->category?->name ?? '—' }}</td>
                    <td style="padding:12px 18px;text-align:right;font-size:16px;font-weight:700;color:var(--umber);">{{ $item->quantity }}</td>
                    <td style="padding:12px 18px;text-align:right;color:var(--mauve);">{{ $item->product->unit }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:var(--champagne);">
                    <td colspan="2" style="padding:12px 18px;font-weight:700;color:var(--umber);font-size:13px;text-transform:uppercase;letter-spacing:.05em;">Order total</td>
                    <td colspan="2" style="padding:12px 18px;text-align:right;font-size:18px;font-weight:700;color:var(--umber);">₹{{ number_format($order->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Sidebar --}}
    <div style="display:flex;flex-direction:column;gap:1rem;">

        {{-- Delivery info --}}
        <div class="s-card" style="margin-bottom:0;">
            <div class="s-card-head">
                <span class="s-card-title">
                    <i class="ti ti-map-pin" aria-hidden="true" style="font-size:16px;vertical-align:-2px;margin-right:6px;"></i>
                    Delivery info
                </span>
            </div>
            <div style="padding:16px 18px;">
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:var(--mauve);margin-bottom:6px;font-weight:700;">Address</div>
                <p style="font-size:13px;color:var(--umber);margin:0 0 14px;line-height:1.5;">{{ $order->delivery_address }}</p>
                
                @if($order->notes)
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:var(--mauve);margin-bottom:6px;font-weight:700;">Notes</div>
                <p style="font-size:13px;color:var(--umber);margin:0 0 14px;padding:10px 14px;background:var(--ivory);border:1px solid rgba(196,164,132,0.25);border-radius:8px;line-height:1.5;">
                    {{ $order->notes }}
                </p>
                @endif

                {{-- New "Last Updated By" Field --}}
                @if($order->updatedByStaff)
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:var(--mauve);margin-bottom:4px;font-weight:700;">Last Handled By</div>
                <p style="font-size:12px;color:var(--olive);margin:0;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
                    <i class="ti ti-user-check" style="font-size:14px;"></i> {{ $order->updatedByStaff->name }}
                </p>
                @endif
            </div>
        </div>

        {{-- Advance status --}}
        @if(isset($nextStatus[$order->status]))
        <div class="s-card" style="margin-bottom:0;">
            <div class="s-card-head">
                <span class="s-card-title">
                    <i class="ti ti-arrow-right" aria-hidden="true" style="font-size:16px;vertical-align:-2px;margin-right:6px;"></i>
                    Advance order
                </span>
            </div>
            <div style="padding:16px 18px;">
                <form action="{{ route('staff.orders.status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="{{ $nextStatus[$order->status] }}">
                    <button type="submit" class="s-open-btn" style="width:100%;padding:12px;text-align:center;background:var(--umber);color:var(--ivory);border-color:var(--umber);font-size:13px;border-radius:8px;display:block;">
                        {{ $nextLabels[$order->status] }}
                        <i class="ti ti-arrow-right" aria-hidden="true" style="font-size:14px;vertical-align:-2px;margin-left:4px;"></i>
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection