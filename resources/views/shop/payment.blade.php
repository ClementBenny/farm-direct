@extends('layouts.public')

@section('title', 'Payment — Farm Direct')

@section('content')
<div class="page-wrap" style="max-width:560px;">

    <a href="{{ route('shop.orders') }}" class="back-link">
        <i class="ph ph-arrow-left"></i> Back to orders
    </a>

    <h1 class="page-heading">Payment</h1>
    <p class="page-sub">Complete your payment to confirm the order</p>

    @include('partials.flash')

    {{-- Order summary strip --}}
    <div style="display:flex; justify-content:space-between; align-items:center; padding:16px 24px; background:var(--champagne); border:1.5px solid rgba(75,54,33,0.18); border-radius:14px; margin-bottom:2rem;">
        <div>
            <p style="font-size:11px; letter-spacing:0.12em; text-transform:uppercase; color:var(--olive); margin-bottom:2px;">Order</p>
            <p style="font-size:14px; font-weight:600; color:var(--umber);">#{{ strtoupper(substr(md5($order->id . $order->created_at), 0, 8)) }}</p>
        </div>
        <div style="text-align:right;">
            <p style="font-size:11px; letter-spacing:0.12em; text-transform:uppercase; color:var(--olive); margin-bottom:2px;">Amount Due</p>
            <p style="font-family:'Cormorant Garamond',serif; font-size:1.8rem; font-weight:600; color:var(--umber);">₹{{ number_format($order->total, 2) }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('shop.payment.process', $order) }}" id="payment-form" novalidate>
        @csrf
        <input type="hidden" name="payment_method" value="card">

        <div class="fd-card">
            <div class="fd-card-label"><i class="ph ph-credit-card"></i> Card Details</div>

            <div style="display:flex; flex-direction:column; gap:18px;">

                {{-- Card number --}}
                <div>
                    <label style="font-size:11px; font-weight:500; letter-spacing:0.12em; text-transform:uppercase; color:var(--olive); display:block; margin-bottom:7px;">Card Number</label>
                    <input type="text" id="card_number" placeholder="1234 5678 9012 3456" maxlength="19" autocomplete="cc-number"
                        style="width:100%; padding:0.65rem 1rem; font-family:'Jost',sans-serif; font-size:14px; color:var(--umber); background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); border-radius:10px; outline:none; transition:border-color 0.2s;">
                    <p class="field-error" id="err-number" style="display:none; font-size:12px; color:#8c2828; margin-top:4px;"></p>
                </div>

                {{-- Cardholder name --}}
                <div>
                    <label style="font-size:11px; font-weight:500; letter-spacing:0.12em; text-transform:uppercase; color:var(--olive); display:block; margin-bottom:7px;">Cardholder Name</label>
                    <input type="text" id="card_name" placeholder="Name on card" autocomplete="cc-name"
                        style="width:100%; padding:0.65rem 1rem; font-family:'Jost',sans-serif; font-size:14px; color:var(--umber); background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); border-radius:10px; outline:none; transition:border-color 0.2s;">
                    <p class="field-error" id="err-name" style="display:none; font-size:12px; color:#8c2828; margin-top:4px;"></p>
                </div>

                {{-- Expiry + CVV --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                    <div>
                        <label style="font-size:11px; font-weight:500; letter-spacing:0.12em; text-transform:uppercase; color:var(--olive); display:block; margin-bottom:7px;">Expiry</label>
                        <input type="text" id="card_expiry" placeholder="MM / YY" maxlength="7" autocomplete="cc-exp"
                            style="width:100%; padding:0.65rem 1rem; font-family:'Jost',sans-serif; font-size:14px; color:var(--umber); background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); border-radius:10px; outline:none; transition:border-color 0.2s;">
                        <p class="field-error" id="err-expiry" style="display:none; font-size:12px; color:#8c2828; margin-top:4px;"></p>
                    </div>
                    <div>
                        <label style="font-size:11px; font-weight:500; letter-spacing:0.12em; text-transform:uppercase; color:var(--olive); display:block; margin-bottom:7px;">CVV</label>
                        <input type="text" id="card_cvv" placeholder="•••" maxlength="4" autocomplete="cc-csc"
                            style="width:100%; padding:0.65rem 1rem; font-family:'Jost',sans-serif; font-size:14px; color:var(--umber); background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); border-radius:10px; outline:none; transition:border-color 0.2s;">
                        <p class="field-error" id="err-cvv" style="display:none; font-size:12px; color:#8c2828; margin-top:4px;"></p>
                    </div>
                </div>

            </div>
        </div>

        <button type="submit" class="btn-primary" id="pay-btn" style="width:100%; text-align:center; padding:16px; margin-top:0.5rem;">
            Pay ₹{{ number_format($order->total, 2) }}
        </button>

        <p style="text-align:center; font-size:11px; color:var(--mauve); margin-top:14px; letter-spacing:0.04em;">
            <i class="ph ph-lock"></i> This is a secure mock payment — no real transaction will occur
        </p>
    </form>
</div>

@push('scripts')
<script>
    const cardNumber = document.getElementById('card_number');
    const cardName   = document.getElementById('card_name');
    const cardExpiry = document.getElementById('card_expiry');
    const cardCvv    = document.getElementById('card_cvv');

    // ── Formatters ──
    cardNumber.addEventListener('input', e => {
        e.target.value = e.target.value.replace(/\D/g, '').slice(0, 16).replace(/(.{4})/g, '$1 ').trim();
    });

    cardExpiry.addEventListener('input', e => {
        let v = e.target.value.replace(/\D/g, '').slice(0, 4);
        if (v.length >= 3) v = v.slice(0, 2) + ' / ' + v.slice(2);
        e.target.value = v;
    });

    cardCvv.addEventListener('input', e => {
        e.target.value = e.target.value.replace(/\D/g, '').slice(0, 4);
    });

    // ── Validators ──
    function luhn(num) {
        let sum = 0, alt = false;
        for (let i = num.length - 1; i >= 0; i--) {
            let n = parseInt(num[i]);
            if (alt) { n *= 2; if (n > 9) n -= 9; }
            sum += n;
            alt = !alt;
        }
        return sum % 10 === 0;
    }

    function showError(id, msg) {
        const el = document.getElementById(id);
        const input = el.previousElementSibling;
        el.textContent = msg;
        el.style.display = msg ? 'block' : 'none';
        input.style.borderColor = msg ? '#8c2828' : 'rgba(75,54,33,0.18)';
        return !!msg;
    }

    function validateNumber() {
        const digits = cardNumber.value.replace(/\s/g, '');
        if (!digits) return showError('err-number', 'Card number is required.');
        if (digits.length < 13) return showError('err-number', 'Card number is too short.');
        if (!luhn(digits)) return showError('err-number', 'Invalid card number.');
        return showError('err-number', '');
    }

    function validateName() {
        const v = cardName.value.trim();
        if (!v) return showError('err-name', 'Cardholder name is required.');
        if (v.length < 2) return showError('err-name', 'Enter a valid name.');
        return showError('err-name', '');
    }

    function validateExpiry() {
        const parts = cardExpiry.value.split('/').map(s => s.trim());
        if (parts.length !== 2 || !parts[0] || !parts[1]) return showError('err-expiry', 'Enter expiry as MM / YY.');
        const month = parseInt(parts[0]);
        const year  = parseInt('20' + parts[1]);
        if (month < 1 || month > 12) return showError('err-expiry', 'Invalid month.');
        const now = new Date();
        const exp = new Date(year, month - 1);
        if (exp < new Date(now.getFullYear(), now.getMonth())) return showError('err-expiry', 'This card has expired.');
        return showError('err-expiry', '');
    }

    function validateCvv() {
        const v = cardCvv.value.trim();
        if (!v) return showError('err-cvv', 'CVV is required.');
        if (v.length < 3) return showError('err-cvv', 'CVV must be 3 or 4 digits.');
        return showError('err-cvv', '');
    }

    // Validate on blur
    cardNumber.addEventListener('blur', validateNumber);
    cardName.addEventListener('blur', validateName);
    cardExpiry.addEventListener('blur', validateExpiry);
    cardCvv.addEventListener('blur', validateCvv);

    // ── Submit ──
    document.getElementById('payment-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const hasErrors = [validateNumber(), validateName(), validateExpiry(), validateCvv()].some(Boolean);
        if (hasErrors) return;

        const btn = document.getElementById('pay-btn');
        btn.textContent = 'Processing…';
        btn.disabled = true;
        btn.style.opacity = '0.7';

        this.submit();
    });
</script>
@endpush

@endsection