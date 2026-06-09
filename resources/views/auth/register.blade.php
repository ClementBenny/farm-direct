@extends('layouts.auth')
@section('page-title', 'Register — Farm Direct')
@section('content')

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="field">
        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
        @error('name') <div class="auth-error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
        @error('email') <div class="auth-error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="new-password">
        @error('password') <div class="auth-error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
    </div>

    <button type="submit" class="auth-btn">Create Account</button>
    <a href="{{ route('login') }}" class="auth-link">Already have an account?</a>
</form>

@endsection