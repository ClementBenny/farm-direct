@extends('layouts.staff')

@section('page-title', 'Stock Levels')

@section('content')

<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:20px;font-weight:700;color:var(--umber);margin:0;">Stock Inventory</h1>
    <span style="font-size:13px;color:var(--mauve);">Sorted by stock level — lowest first</span>
</div>

@if(session('success'))
<div x-data="{ show: true }"
     x-show="show"
     x-init="setTimeout(() => show = false, 3000)"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="background:rgba(128,128,0,0.1);border:1px solid rgba(128,128,0,0.25);color:var(--umber);
            padding:12px 18px;border-radius:10px;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;">
    <span style="font-size:13px;font-weight:600;">
        <i class="ti ti-circle-check" aria-hidden="true" style="font-size:16px;vertical-align:-2px;margin-right:6px;color:var(--olive);"></i>
        {{ session('success') }}
    </span>
    <button @click="show = false" style="background:none;border:none;cursor:pointer;color:var(--mauve);font-size:18px;padding:0;line-height:1;">×</button>
</div>
@endif

<div class="s-card">
    <div class="s-card-head">
        <span class="s-card-title">
            <i class="ti ti-package" aria-hidden="true" style="font-size:16px;vertical-align:-2px;margin-right:6px;"></i>
            All products
        </span>
        <span style="font-size:12px;color:var(--mauve);">{{ $products->count() }} products</span>
    </div>

    @forelse($products as $product)
    <div class="s-row">
        <div style="flex:1;min-width:0;">
            <div class="s-row-ref">
                {{ $product->name }}
                @if(!$product->is_active)
                <span style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--mauve);background:rgba(196,164,132,0.15);padding:2px 7px;border-radius:4px;margin-left:6px;">Inactive</span>
                @endif
            </div>
            <div class="s-row-meta">{{ $product->category?->name ?? '—' }} · per {{ $product->unit }}</div>
        </div>
        <div style="display:flex;align-items:center;gap:12px;flex-shrink:0;">
            <span style="font-size:20px;font-weight:700;min-width:40px;text-align:right;
                  color:{{ $product->stock === 0 ? '#991B1B' : ($product->stock < 5 ? '#92400E' : 'var(--umber)') }};">
                {{ $product->stock }}
            </span>
            @if($product->stock === 0)
                <span class="s-stock-pill" style="background:#FEE2E2;color:#991B1B;min-width:58px;text-align:center;">Critical</span>
            @elseif($product->stock <= 10)
                <span class="s-stock-pill" style="background:#FEF3C7;color:#92400E;min-width:58px;text-align:center;">Low</span>
            @else
                <span class="s-stock-pill" style="background:#D1FAE5;color:#065F46;min-width:58px;text-align:center;">Good</span>
            @endif
            <form action="{{ route('staff.stock.update', $product) }}" method="POST"
                  style="display:flex;align-items:center;gap:6px;">
                @csrf
                @method('PATCH')
                <input type="number" name="stock" value="{{ $product->stock }}" min="0"
                       style="width:70px;border:1.5px solid rgba(196,164,132,0.4);border-radius:8px;
                              padding:6px 10px;font-size:13px;text-align:center;background:var(--ivory);
                              color:var(--umber);outline:none;">
                <button type="submit" class="s-open-btn" style="white-space:nowrap;padding:7px 14px;">
                    Save
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="s-empty">No products found.</div>
    @endforelse
</div>
@endsection