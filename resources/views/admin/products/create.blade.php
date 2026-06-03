@extends('layouts.admin')

@section('page-title', 'Add Product')

@section('content')

<div class="a-page-head">
    <div>
        <a href="{{ route('admin.products.index') }}"
           style="font-size:12px; color:var(--muted); text-decoration:none; display:inline-flex; align-items:center; gap:4px; margin-bottom:6px">
            <i class="ti ti-arrow-left"></i> Back to Products
        </a>
        <h1 class="a-page-title">Add Product</h1>
    </div>
</div>

<form action="{{ route('admin.products.store') }}" method="POST"
      enctype="multipart/form-data" novalidate>
    @csrf
    @include('admin.products._form')
    <div style="margin-top:1.5rem">
        <button type="submit" class="a-btn a-btn-primary">
            <i class="ti ti-plus"></i> Create Product
        </button>
    </div>
</form>

@endsection