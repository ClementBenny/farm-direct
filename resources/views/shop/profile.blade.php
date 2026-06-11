@extends('layouts.public')

@section('title', 'My Profile — Farm Direct')

@section('content')
<div class="page-wrap">

    <h1 class="page-heading">My Profile</h1>
    <p class="page-sub">Manage your account details and saved addresses</p>

    @include('partials.flash')

    {{-- Personal Info --}}
    <div class="fd-card">
        <div class="fd-card-label"><i class="ph ph-user"></i> Personal Information</div>
        <form method="POST" action="{{ route('shop.profile.info') }}">
            @csrf
            <div class="delivery-grid" style="margin-bottom:28px">
                <div class="delivery-field">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                        style="width:100%; padding:0.65rem 0.9rem; font-family:'Jost',sans-serif; font-size:15px; color:var(--umber); background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); border-radius:10px; outline:none;">
                    @error('name') <p style="font-size:12px; color:#8c2828; margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                <div class="delivery-field">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                        style="width:100%; padding:0.65rem 0.9rem; font-family:'Jost',sans-serif; font-size:15px; color:var(--umber); background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); border-radius:10px; outline:none;">
                    @error('email') <p style="font-size:12px; color:#8c2828; margin-top:4px;">{{ $message }}</p> @enderror
                </div>
            </div>
            <button type="submit" class="btn-primary" style="padding:12px 32px; font-size:12px;">Save Changes</button>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="fd-card">
        <div class="fd-card-label"><i class="ph ph-lock"></i> Change Password</div>
        <form method="POST" action="{{ route('shop.profile.password') }}">
            @csrf
            <div class="delivery-grid" style="margin-bottom:28px">
                <div class="delivery-field">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password"
                        style="width:100%; padding:0.65rem 0.9rem; font-family:'Jost',sans-serif; font-size:15px; color:var(--umber); background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); border-radius:10px; outline:none;">
                    @error('current_password') <p style="font-size:12px; color:#8c2828; margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                <div class="delivery-field">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password"
                        style="width:100%; padding:0.65rem 0.9rem; font-family:'Jost',sans-serif; font-size:15px; color:var(--umber); background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); border-radius:10px; outline:none;">
                    @error('password') <p style="font-size:12px; color:#8c2828; margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                <div class="delivery-field">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        style="width:100%; padding:0.65rem 0.9rem; font-family:'Jost',sans-serif; font-size:15px; color:var(--umber); background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); border-radius:10px; outline:none;">
                </div>
            </div>
            <button type="submit" class="btn-primary" style="padding:12px 32px; font-size:12px;">Update Password</button>
        </form>
    </div>

    {{-- Saved Addresses --}}
    <div class="fd-card">
        <div class="fd-card-label"><i class="ph ph-map-pin"></i> Saved Addresses</div>

        @forelse($addresses as $address)
        <div style="padding:20px 24px; background:var(--ivory); border:1.5px solid rgba(75,54,33,{{ $address->is_default ? '0.35' : '0.12' }}); border-radius:14px; margin-bottom:12px; display:flex; align-items:flex-start; justify-content:space-between; gap:16px;">
            <div style="flex:1">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:6px;">
                    <span style="font-size:12px; font-weight:500; letter-spacing:0.1em; text-transform:uppercase; color:var(--umber);">{{ $address->label }}</span>
                    @if($address->is_default)
                        <span class="status-badge status-confirmed" style="font-size:10px; padding:3px 10px;">Default</span>
                    @endif
                </div>
                <p style="font-size:15px; color:var(--umber); line-height:1.7; opacity:0.8;">
                    {{ $address->address_line }}, {{ $address->city }}, {{ $address->state }} — {{ $address->pincode }}
                </p>
            </div>
            <div style="display:flex; flex-direction:column; gap:8px; flex-shrink:0; align-items:flex-end;">
                @if(!$address->is_default)
                <form method="POST" action="{{ route('shop.profile.addresses.default', $address) }}">
                    @csrf
                    <button type="submit" class="btn-ghost" style="font-size:12px; background:none; border-bottom:1px solid rgba(196,164,132,0.4); cursor:pointer; font-family:'Jost',sans-serif;">Set default</button>
                </form>
                @endif
                <form method="POST" action="{{ route('shop.profile.addresses.destroy', $address) }}">
                    @csrf @method('DELETE')
                    <button type="submit" style="font-size:12px; color:#8c2828; background:none; border:none; cursor:pointer; font-family:'Jost',sans-serif; letter-spacing:0.04em;">Remove</button>
                </form>
            </div>
        </div>
        @empty
        <div style="text-align:center; padding:40px 0; color:var(--mauve); font-size:15px;">
            No saved addresses yet.
        </div>
        @endforelse

        <div class="fd-divider" style="margin-top:28px;"></div>

        {{-- Add address form --}}
        <div style="margin-top:4px;">
            <p style="font-size:11px; font-weight:500; letter-spacing:0.18em; text-transform:uppercase; color:var(--olive); margin-bottom:20px;">Add New Address</p>
            <form method="POST" action="{{ route('shop.profile.addresses.store') }}">
                @csrf
                <div class="delivery-grid" style="margin-bottom:16px;">
                    <div class="delivery-field">
                        <label for="label">Label</label>
                        <input type="text" id="label" name="label" value="{{ old('label', 'Home') }}" placeholder="Home, Office…"
                            style="width:100%; padding:0.65rem 0.9rem; font-family:'Jost',sans-serif; font-size:15px; color:var(--umber); background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); border-radius:10px; outline:none;">
                        @error('label') <p style="font-size:12px; color:#8c2828; margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                    <div class="delivery-field">
                        <label for="pincode">Pincode</label>
                        <input type="text" id="pincode" name="pincode" value="{{ old('pincode') }}" placeholder="682301"
                            style="width:100%; padding:0.65rem 0.9rem; font-family:'Jost',sans-serif; font-size:15px; color:var(--umber); background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); border-radius:10px; outline:none;">
                        @error('pincode') <p style="font-size:12px; color:#8c2828; margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                    <div class="delivery-field" style="grid-column:span 2;">
                        <label for="address_line">Address</label>
                        <input type="text" id="address_line" name="address_line" value="{{ old('address_line') }}" placeholder="House no, Street, Landmark"
                            style="width:100%; padding:0.65rem 0.9rem; font-family:'Jost',sans-serif; font-size:15px; color:var(--umber); background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); border-radius:10px; outline:none;">
                        @error('address_line') <p style="font-size:12px; color:#8c2828; margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                    <div class="delivery-field">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" placeholder="Angamaly"
                            style="width:100%; padding:0.65rem 0.9rem; font-family:'Jost',sans-serif; font-size:15px; color:var(--umber); background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); border-radius:10px; outline:none;">
                        @error('city') <p style="font-size:12px; color:#8c2828; margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                    <div class="delivery-field">
                        <label for="state">State</label>
                        <input type="text" id="state" name="state" value="{{ old('state', 'Kerala') }}"
                            style="width:100%; padding:0.65rem 0.9rem; font-family:'Jost',sans-serif; font-size:15px; color:var(--umber); background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); border-radius:10px; outline:none;">
                        @error('state') <p style="font-size:12px; color:#8c2828; margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:24px; flex-wrap:wrap;">
                    <label style="display:flex; align-items:center; gap:8px; text-transform:none; letter-spacing:0; font-size:14px; color:var(--umber); cursor:pointer;">
                        <input type="checkbox" name="is_default" value="1" style="accent-color:var(--olive); width:15px; height:15px;">
                        Set as default address
                    </label>
                    <button type="submit" class="btn-primary" style="padding:12px 32px; font-size:12px;">Add Address</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection