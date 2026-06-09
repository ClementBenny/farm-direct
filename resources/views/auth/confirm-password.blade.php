@extends('layouts.auth')
@section('page-title', 'Confirm Password — Farm Direct')
@section('content')

<p class="auth-hint">Please confirm your password before continuing.</p>

<form method="POST" action="{{ route('password.confirm') }}">
    @csrf

    <div class="field">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="current-password">
        @error('password') <div class="auth-error">{{ $message }}</div> @enderror
    </div>

    <button type="submit" class="auth-btn">Confirm</button>
</form>

@endsection