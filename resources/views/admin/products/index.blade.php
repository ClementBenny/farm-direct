@extends('layouts.admin')

@section('page-title', 'Products')

@section('content')

<div class="a-page-head">
    <div>
        <h1 class="a-page-title">Products</h1>
        <p class="a-page-sub">Manage your product catalogue</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="a-btn a-btn-primary">
        <i class="ti ti-plus"></i> Add Product
    </a>
</div>

<div class="a-card">
    <div class="a-card-body" style="padding:0">
        <table class="a-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th class="right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}"
                                 alt="{{ $product->name }}"
                                 style="width:44px; height:44px; object-fit:cover; border-radius:8px; border:1px solid var(--border)">
                        @else
                            <div style="width:44px; height:44px; border-radius:8px; background:var(--bg);
                                        border:1px solid var(--border); display:flex; align-items:center;
                                        justify-content:center; font-size:1.25rem">
                                🥬
                            </div>
                        @endif
                    </td>
                    <td>
                        <p style="font-weight:600; color:var(--dark); margin:0">{{ $product->name }}</p>
                        @if($product->description)
                            <p style="font-size:11px; color:var(--muted); margin:2px 0 0; max-width:220px;
                                      overflow:hidden; text-overflow:ellipsis; white-space:nowrap">
                                {{ $product->description }}
                            </p>
                        @endif
                    </td>
                    <td style="color:var(--muted)">{{ $product->category?->name ?? '—' }}</td>
                    <td>
                        <p style="font-weight:600; margin:0">₹{{ number_format($product->price, 2) }}</p>
                        @if($product->bulk_price)
                            <p style="font-size:11px; color:var(--muted); margin:2px 0 0">
                                Bulk: ₹{{ number_format($product->bulk_price, 2) }}
                            </p>
                        @endif
                    </td>
                    <td style="color:var(--muted)">{{ $product->stock }} {{ $product->unit }}</td>
                    <td>
                        @if($product->is_active)
                            <span class="a-pill a-pill-good">Active</span>
                        @else
                            <span class="a-pill" style="background:var(--bg); color:var(--muted)">Inactive</span>
                        @endif
                    </td>
                    <td class="right" style="white-space:nowrap">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="a-btn a-btn-ghost" style="font-size:0.8rem; padding:0.3rem 0.75rem">
                            <i class="ti ti-pencil"></i> Edit
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                              class="inline" onsubmit="return confirm('Delete {{ $product->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="a-btn a-btn-danger" style="font-size:0.8rem; padding:0.3rem 0.75rem">
                                <i class="ti ti-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="a-empty">
                            <i class="ti ti-plant-off" style="font-size:2rem; margin-bottom:0.5rem; display:block"></i>
                            No products yet.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection