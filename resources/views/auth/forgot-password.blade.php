@extends('layouts.auth')
@section('page-title', 'Reset Password — Farm Direct')
@section('content')

@if(session('status'))
    <div class="auth-status">{{ session('status') }}</div>
@endif

<p class="auth-hint">Enter your email and we'll send you a password reset link.</p>

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="field">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email') <div class="auth-error">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="auth-btn">Send Reset Link</button>
    <a href="{{ route('login') }}" class="auth-link">Back to sign in</a>
</form>

@endsection