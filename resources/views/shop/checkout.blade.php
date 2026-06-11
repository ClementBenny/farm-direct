@extends('layouts.public')

@section('title', 'Checkout')

@section('content')
<div class="page-wrap" style="max-width:1000px;">

    <h1 class="page-heading">Checkout</h1>
    <p class="page-sub">Review your order and confirm delivery details</p>

    <div style="display:grid; grid-template-columns:1fr 1.4fr; gap:2rem; align-items:start;">

        {{-- ORDER SUMMARY --}}
        <div class="fd-card fd-card--flush" style="position:sticky; top:100px;">
            <div style="padding:32px 36px; border-bottom:1px solid rgba(75,54,33,0.12);">
                <div class="fd-card-label"><i class="ph ph-receipt"></i> Your Bill</div>
                <div style="display:flex; flex-direction:column; gap:14px;">
                    @foreach($cart as $productId => $quantity)
                        @if($products->has($productId))
                        @php $product = $products[$productId]; @endphp
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px;">
                            <div>
                                <p style="font-size:14px; font-weight:500; color:var(--umber);">{{ $product->name }}</p>
                                <p style="font-size:12px; color:var(--mauve);">× {{ $quantity }}</p>
                            </div>
                            <p style="font-size:14px; font-weight:600; color:var(--umber); white-space:nowrap;">₹{{ number_format($product->price * $quantity, 2) }}</p>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div style="padding:24px 36px; display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:11px; letter-spacing:0.18em; text-transform:uppercase; color:var(--olive);">Total</span>
                <span style="font-family:'Cormorant Garamond',serif; font-size:2rem; font-weight:600; color:var(--umber);">₹{{ number_format($total, 2) }}</span>
            </div>
        </div>

        {{-- DELIVERY DETAILS --}}
        <div>
            <form method="POST" action="{{ route('shop.checkout.store') }}">
                @csrf

                {{-- Saved addresses --}}
                @if($addresses->isNotEmpty())
                <div class="fd-card" style="margin-bottom:1.25rem;">
                    <div class="fd-card-label"><i class="ph ph-map-pin"></i> Saved Addresses</div>
                    <div style="display:flex; flex-direction:column; gap:10px; margin-bottom:20px;">
                        @foreach($addresses as $address)
                        <label style="cursor:pointer;">
                            <input type="radio" name="address_id" value="{{ $address->id }}"
                                {{ ($address->is_default || old('address_id') == $address->id) && old('address_id') !== 'manual' ? 'checked' : '' }}
                                style="display:none;" class="addr-radio">
                            <div class="addr-card" style="padding:16px 20px; border:1.5px solid rgba(75,54,33,0.15); border-radius:12px; background:var(--ivory); transition:border-color 0.2s;">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                                    <span style="font-size:12px; font-weight:600; letter-spacing:0.08em; text-transform:uppercase; color:var(--umber);">{{ $address->label }}</span>
                                    @if($address->is_default)
                                        <span class="status-badge status-confirmed" style="font-size:10px; padding:2px 10px;">Default</span>
                                    @endif
                                </div>
                                <p style="font-size:14px; color:var(--umber); opacity:0.75; line-height:1.6;">
                                    {{ $address->address_line }}, {{ $address->city }}, {{ $address->state }} — {{ $address->pincode }}
                                </p>
                            </div>
                        </label>
                        @endforeach

                        {{-- Manual entry option --}}
                        <label style="cursor:pointer;">
                            <input type="radio" name="address_id" value="manual"
                                {{ old('address_id') === 'manual' ? 'checked' : '' }}
                                style="display:none;" class="addr-radio" id="radio-manual">
                            <div class="addr-card" style="padding:16px 20px; border:1.5px solid rgba(75,54,33,0.15); border-radius:12px; background:var(--ivory); transition:border-color 0.2s;">
                                <span style="font-size:12px; font-weight:600; letter-spacing:0.08em; text-transform:uppercase; color:var(--mauve);">
                                    <i class="ph ph-plus"></i> Enter a different address
                                </span>
                            </div>
                        </label>
                    </div>

                    {{-- Manual address textarea (shown only when manual is selected) --}}
                    <div id="manual-address-wrap" style="display:{{ old('address_id') === 'manual' ? 'block' : 'none' }}">
                        <label style="font-size:11px; font-weight:500; letter-spacing:0.18em; text-transform:uppercase; color:var(--olive); display:block; margin-bottom:8px;">Delivery Address</label>
                        <textarea name="delivery_address" rows="3" placeholder="House no, Street, City, Pincode"
                            style="width:100%; padding:0.7rem 1rem; font-family:'Jost',sans-serif; font-size:14px; color:var(--umber); background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); border-radius:10px; outline:none; resize:none;">{{ old('delivery_address') }}</textarea>
                        @error('delivery_address') <p style="font-size:12px; color:#8c2828; margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                </div>

                @else
                {{-- No saved addresses — plain textarea --}}
                <div class="fd-card" style="margin-bottom:1.25rem;">
                    <div class="fd-card-label"><i class="ph ph-map-pin"></i> Delivery Address</div>
                    <textarea name="delivery_address" rows="3" placeholder="House no, Street, City, Pincode"
                        style="width:100%; padding:0.7rem 1rem; font-family:'Jost',sans-serif; font-size:14px; color:var(--umber); background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); border-radius:10px; outline:none; resize:none;">{{ old('delivery_address') }}</textarea>
                    @error('delivery_address') <p style="font-size:12px; color:#8c2828; margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                @endif

                {{-- Notes --}}
                <div class="fd-card" style="margin-bottom:1.25rem;">
                    <div class="fd-card-label"><i class="ph ph-note"></i> Order Notes <span style="text-transform:none; letter-spacing:0; font-size:11px; color:var(--mauve);">(optional)</span></div>
                    <textarea name="notes" rows="2" placeholder="Add Delivery instructions."
                        style="width:100%; padding:0.7rem 1rem; font-family:'Jost',sans-serif; font-size:14px; color:var(--umber); background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); border-radius:10px; outline:none; resize:none;">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="btn-primary" style="width:100%; text-align:center; padding:16px;">
                    Place Order
                </button>
            </form>
        </div>

    </div>
</div>

@push('scripts')
<script>
    const radios   = document.querySelectorAll('.addr-radio');
    const cards    = document.querySelectorAll('.addr-card');
    const manualWrap = document.getElementById('manual-address-wrap');

    function updateCards() {
        radios.forEach((radio, i) => {
            cards[i].style.borderColor = radio.checked
                ? 'var(--umber)'
                : 'rgba(75,54,33,0.15)';
            cards[i].style.background = radio.checked
                ? 'var(--champagne)'
                : 'var(--ivory)';
        });
        const manualRadio = document.getElementById('radio-manual');
        manualWrap.style.display = manualRadio?.checked ? 'block' : 'none';
    }

    radios.forEach(r => r.addEventListener('change', updateCards));
    updateCards();
</script>
@endpush

@endsection