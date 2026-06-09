@extends('layouts.auth')

@section('page-title', 'Sign In — Farm Direct')

@section('content')

@if(session('status'))
    <div class="auth-status">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="field">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
        @error('email') <div class="auth-error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="current-password">
        @error('password') <div class="auth-error">{{ $message }}</div> @enderror
    </div>

    <div class="auth-remember">
        <input id="remember_me" type="checkbox" name="remember">
        <span>Remember me</span>
    </div>

    <button type="submit" class="auth-btn">Sign In</button>

    @if(Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="auth-link">Forgot your password?</a>
    @endif
</form>

@endsection