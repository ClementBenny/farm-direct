@extends('layouts.admin')

@section('page-title', 'Edit Product')

@section('content')

<div class="a-page-head">
    <div>
        <a href="{{ route('admin.products.index') }}"
           style="font-size:12px; color:var(--muted); text-decoration:none; display:inline-flex; align-items:center; gap:4px; margin-bottom:6px">
            <i class="ti ti-arrow-left"></i> Back to Products
        </a>
        <h1 class="a-page-title">Edit — {{ $product->name }}</h1>
    </div>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST"
      enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')
    @include('admin.products._form')
    <div style="margin-top:1.5rem">
        <button type="submit" class="a-btn a-btn-primary">
            <i class="ti ti-device-floppy"></i> Save Changes
        </button>
    </div>
</form>

@endsection