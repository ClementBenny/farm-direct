@extends('layouts.public')

@section('title', 'Your Cart — Farm Direct Wholesale')

@section('content')

<style>
    .qty-input {
        width: 64px; text-align: center;
        border: 1.5px solid rgba(75,54,33,0.22);
        border-radius: 999px; padding: 6px 10px;
        font-size: 14px; color: var(--umber);
        background: var(--ivory); font-family: 'Jost', sans-serif;
        transition: border-color 0.2s, box-shadow 0.2s;
        -moz-appearance: textfield;
    }
    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .qty-input:focus {
        outline: none; border-color: var(--umber);
        box-shadow: 0 0 0 3px rgba(75,54,33,0.1);
    }
    .remove-btn {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 12px; letter-spacing: 0.06em; color: rgba(140,40,40,0.6);
        background: none; border: none; cursor: pointer;
        font-family: 'Jost', sans-serif; font-weight: 400;
        padding: 0; transition: color 0.2s;
    }
    .remove-btn:hover { color: #8c2828; }
    .remove-btn i { font-size: 14px; }
    .cart-actions {
        display: flex; align-items: center;
        justify-content: space-between; gap: 16px; flex-wrap: wrap;
    }
    @media (max-width: 640px) {
        .cart-actions { flex-direction: column; align-items: stretch; text-align: center; }
    }
</style>

<div class="page-wrap">

    <p class="section-label">Wholesale Account</p>
    <h1 class="page-heading">Your Cart</h1>

    @if(empty($cart))
        <div class="empty-state">
            <div class="empty-state-icon"><i class="ph-light ph-shopping-cart"></i></div>
            <h3>Your cart is empty</h3>
            <p>Add some products to get started.</p>
            <a href="{{ route('wholesale.index') }}" class="btn-primary">Browse wholesale</a>
        </div>
    @else
        <p class="page-sub">{{ count($cart) }} {{ Str::plural('item', count($cart)) }} in your cart</p>

        <div class="fd-card fd-card--flush">
            <table class="fd-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Bulk Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $productId => $quantity)
                        @if($products->has($productId))
                            @php $product = $products[$productId]; @endphp
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td class="muted">₹{{ number_format($product->bulk_price, 2) }} / {{ $product->unit }}</td>
                                <td>
                                    <form action="{{ route('wholesale.cart.update') }}" method="POST" style="display:flex;justify-content:flex-end;">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $productId }}">
                                        <input type="number" name="quantity"
                                               value="{{ $quantity }}"
                                               min="{{ $product->min_order_qty }}"
                                               max="{{ $product->stock }}"
                                               onchange="this.form.submit()"
                                               class="qty-input">
                                    </form>
                                </td>
                                <td>₹{{ number_format($product->bulk_price * $quantity, 2) }}</td>
                                <td style="text-align:center;">
                                    <form action="{{ route('wholesale.cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $productId }}">
                                        <button type="submit" class="remove-btn">
                                            <i class="ph ph-trash"></i> Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Order Total</td>
                        <td colspan="2">₹{{ number_format($total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="cart-actions">
            <a href="{{ route('wholesale.index') }}" class="back-link">
                <i class="ph ph-arrow-left"></i> Continue shopping
            </a>
            <a href="{{ route('wholesale.checkout') }}" class="btn-primary">
                Proceed to checkout &nbsp;<i class="ph ph-arrow-right"></i>
            </a>
        </div>
    @endif

</div>

@endsection