@extends('layouts.admin')

@section('page-title', 'Categories')

@section('content')

<div class="a-page-head">
    <div>
        <h1 class="a-page-title">Categories</h1>
        <p class="a-page-sub">Manage product categories</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="a-btn a-btn-primary">
        <i class="ti ti-plus"></i> Add Category
    </a>
</div>

<div class="a-card">
    <div class="a-card-body" style="padding:0">
        <table class="a-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Products</th>
                    <th class="right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td style="font-weight:600">{{ $category->name }}</td>
                    <td style="font-family:monospace; font-size:0.75rem; font-weight:1000">{{ $category->slug }}</td>
                    <td style="font-weight:600">{{ $category->products_count }}</td>
                    <td class="right" style="white-space:nowrap">
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="a-btn a-btn-ghost" style="font-size:0.8rem; padding:0.3rem 0.75rem">
                            <i class="ti ti-pencil"></i> Edit
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                              class="inline" onsubmit="return confirm('Delete {{ $category->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="a-btn a-btn-danger" style="font-size:0.8rem; padding:0.3rem 0.75rem">
                                <i class="ti ti-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <div class="a-empty">
                            <i class="ti ti-tag-off" style="font-size:2rem; margin-bottom:0.5rem; display:block"></i>
                            No categories yet.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection