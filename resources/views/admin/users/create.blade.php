@extends('layouts.admin')

@section('page-title', 'Add User')

@section('content')

<div class="a-page-head">
    <div>
        <a href="{{ route('admin.users.index') }}"
           style="font-size:12px; color:var(--muted); text-decoration:none; display:inline-flex; align-items:center; gap:4px; margin-bottom:6px">
            <i class="ti ti-arrow-left"></i> Back to Users
        </a>
        <h1 class="a-page-title">Add User</h1>
    </div>
</div>

<div class="a-card" style="max-width:520px">
    <div class="a-card-head">
        <span class="a-card-title"><i class="ti ti-user-plus"></i> New User</span>
    </div>
    <div class="a-card-body">
        <form action="{{ route('admin.users.store') }}" method="POST" novalidate>
            @csrf

            <div class="a-form-group">
                <label class="a-label">Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="a-input @error('name') border-red-400 @enderror">
                @error('name') <p style="color:#dc2626; font-size:11px; margin-top:4px">{{ $message }}</p> @enderror
            </div>

            <div class="a-form-group">
                <label class="a-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="a-input @error('email') border-red-400 @enderror">
                @error('email') <p style="color:#dc2626; font-size:11px; margin-top:4px">{{ $message }}</p> @enderror
            </div>

            <div class="a-form-group">
                <label class="a-label">Role</label>
                <select name="role" class="a-input @error('role') border-red-400 @enderror">
                    <option value="">— Select a role —</option>
                    @foreach(['admin', 'customer', 'shop', 'staff'] as $role)
                        <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
                @error('role') <p style="color:#dc2626; font-size:11px; margin-top:4px">{{ $message }}</p> @enderror
            </div>

            <div class="a-form-group">
                <label class="a-label">Password</label>
                <input type="password" name="password"
                       class="a-input @error('password') border-red-400 @enderror">
                @error('password') <p style="color:#dc2626; font-size:11px; margin-top:4px">{{ $message }}</p> @enderror
            </div>

            <div class="a-form-group">
                <label class="a-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="a-input">
            </div>

            <button type="submit" class="a-btn a-btn-primary" style="width:100%; justify-content:center">
                <i class="ti ti-plus"></i> Create User
            </button>
        </form>
    </div>
</div>

@endsection