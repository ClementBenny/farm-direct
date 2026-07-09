@extends('layouts.admin')

@section('page-title', 'Products Report')

@section('content')
<div class="a-page-head">
    <div>
        <div class="a-page-title">Products Report</div>
        <div class="a-page-sub">{{ $rows->count() }} products</div>
    </div>
</div>

<div class="a-card">
    <div class="a-card-body">
        <form method="GET" action="{{ route('admin.reports.products') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
            <div class="a-form-group" style="margin-bottom:0;">
                <label class="a-label">Category</label>
                <select name="category_id" class="a-input">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (string) request('category_id') === (string) $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="a-btn a-btn-primary"><i class="ti ti-filter"></i> Filter</button>

            @if(request()->filled('category_id'))
                <a href="{{ route('admin.reports.products') }}" class="a-btn a-btn-ghost">Clear</a>
            @endif
        </form>
    </div>

    <div style="overflow-x:auto;">
    <table class="a-table">
        <thead>
        <tr>
            <x-a-sortable-th column="name" label="Name" :sort="$sort" :direction="$direction" />
            <th>Category</th>
            <x-a-sortable-th column="price" label="Price" :sort="$sort" :direction="$direction" />
            <x-a-sortable-th column="stock" label="Stock" :sort="$sort" :direction="$direction" />
            <x-a-sortable-th column="created_at" label="Added" :sort="$sort" :direction="$direction" />
        </tr>
        </thead>
        <tbody>
        @forelse($rows as $row)
            <tr>
                <td>{{ $row->name }}</td>
                <td>{{ $row->category->name ?? '—' }}</td>
                <td>₹{{ number_format($row->price, 2) }}</td>
                <td>{{ $row->stock }}</td>
                <td>{{ $row->created_at->format('d M Y') }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="a-empty">No products found.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection