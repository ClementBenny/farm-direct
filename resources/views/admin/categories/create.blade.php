@extends('layouts.admin')

@section('page-title', 'Add Category')

@section('content')

<div class="a-page-head">
    <div>
        <a href="{{ route('admin.categories.index') }}"
           style="font-size:12px; color:var(--muted); text-decoration:none; display:inline-flex; align-items:center; gap:4px; margin-bottom:6px">
            <i class="ti ti-arrow-left"></i> Back to Categories
        </a>
        <h1 class="a-page-title">Add Category</h1>
    </div>
</div>

<div class="a-card" style="max-width:480px">
    <div class="a-card-head">
        <span class="a-card-title"><i class="ti ti-tag-plus"></i> New Category</span>
    </div>
    <div class="a-card-body">
        <form action="{{ route('admin.categories.store') }}" method="POST" novalidate>
            @csrf

            <div class="a-form-group">
                <label class="a-label">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" autofocus
                       class="a-input @error('name') border-red-400 @enderror"
                       placeholder="e.g. Vegetables">
                @error('name')
                    <p style="color:#dc2626; font-size:11px; margin-top:4px">{{ $message }}</p>
                @enderror
                <p style="font-size:11px; color:var(--muted); margin-top:4px">The slug will be generated automatically.</p>
            </div>

            <button type="submit" class="a-btn a-btn-primary" style="width:100%; justify-content:center">
                <i class="ti ti-plus"></i> Create Category
            </button>
        </form>
    </div>
</div>

@endsection