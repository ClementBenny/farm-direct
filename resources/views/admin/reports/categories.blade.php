@extends('layouts.admin')

@section('page-title', 'Categories Report')

@section('content')
<div class="a-page-head">
    <div>
        <div class="a-page-title">Categories Report</div>
        <div class="a-page-sub">{{ $rows->count() }} categories</div>
    </div>
   
</div>

<div class="a-card">
    <div style="overflow-x:auto;">
    <table class="a-table">
        <thead>
        <tr>
            <x-a-sortable-th column="name" label="Name" :sort="$sort" :direction="$direction" />
            <th class="right">Products</th>
            <x-a-sortable-th column="created_at" label="Created" :sort="$sort" :direction="$direction" />
        </tr>
        </thead>
        <tbody>
        @forelse($rows as $row)
            <tr>
                <td>{{ $row->name }}</td>
                <td class="right">{{ $row->products_count }}</td>
                <td>{{ $row->created_at->format('d M Y') }}</td>
            </tr>
        @empty
            <tr><td colspan="3" class="a-empty">No categories found.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection