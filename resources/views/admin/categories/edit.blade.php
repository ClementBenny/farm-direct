@extends('layouts.admin')

@section('page-title', 'Edit Category')

@section('content')

<div class="a-page-head">
    <div>
        <a href="{{ route('admin.categories.index') }}"
           style="font-size:12px; color:var(--muted); text-decoration:none; display:inline-flex; align-items:center; gap:4px; margin-bottom:6px">
            <i class="ti ti-arrow-left"></i> Back to Categories
        </a>
        <h1 class="a-page-title">Edit — {{ $category->name }}</h1>
    </div>
</div>

<div class="a-card" style="max-width:480px">
    <div class="a-card-head">
        <span class="a-card-title"><i class="ti ti-tag"></i> Category Details</span>
    </div>
    <div class="a-card-body">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="a-form-group">
                <label class="a-label">Name</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" autofocus
                       class="a-input @error('name') border-red-400 @enderror">
                @error('name')
                    <p style="color:#dc2626; font-size:11px; margin-top:4px">{{ $message }}</p>
                @enderror
            </div>

            <div class="a-form-group">
                <label class="a-label">Current Slug</label>
                <p style="font-family:monospace; font-size:12px; color:var(--muted); background:var(--bg);
                          padding:9px 12px; border-radius:8px; border:1px solid var(--border); margin:0">
                    {{ $category->slug }}
                </p>
                <p style="font-size:11px; color:var(--muted); margin-top:4px">Slug updates automatically on save.</p>
            </div>

            <button type="submit" class="a-btn a-btn-primary" style="width:100%; justify-content:center">
                <i class="ti ti-device-floppy"></i> Save Changes
            </button>
        </form>
    </div>
</div>

@endsection