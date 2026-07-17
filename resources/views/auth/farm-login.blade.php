@extends('layouts.auth')

@section('page-title', 'Management Sign In — Farm Direct')

@section('content')

@if(session('status'))
    <div class="auth-status">{{ session('status') }}</div>
@endif

<div class="auth-card">
    <div class="auth-tabs" >
        <label for="role">Admin / Staff</label>
    </div>

    <form method="POST" action="{{ route('farm.login.store') }}" class="auth-form">
        @csrf

        <div class="field-group">
            <div class="field">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@farm.local">
                </div>
                @error('email') <div class="auth-error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                </div>
                @error('password') <div class="auth-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="auth-footer-actions">
            <label class="auth-remember">
                <input id="remember_me" type="checkbox" name="remember">
                <span class="checkmark"></span>
                <span class="label-text">Remember me</span>
            </label>
        </div>

        <button type="submit" class="auth-btn">Sign In</button>
    </form>
</div>

@endsection