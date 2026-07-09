@extends('layouts.admin')

@section('page-title', 'Orders Report')

@section('content')
<div class="a-page-head">
    <div>
        <div class="a-page-title">Orders Report</div>
        <div class="a-page-sub">{{ $rows->count() }} orders</div>
    </div>
</div>

@php
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
    ];
    $statuses = ['pending', 'confirmed', 'picking', 'packed', 'delivered', 'cancelled'];
@endphp

<div class="a-card">
    <div class="a-card-body">
        <form method="GET" action="{{ route('admin.reports.orders') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">

            <div class="a-form-group" style="margin-bottom:0;">
                <label class="a-label">From date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="a-input">
            </div>

            <div class="a-form-group" style="margin-bottom:0;">
                <label class="a-label">To date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="a-input">
            </div>

            <div class="a-form-group" style="margin-bottom:0;">
                <label class="a-label">Month</label>
                <select name="month" class="a-input">
                    <option value="">All months</option>
                    @foreach($months as $num => $label)
                        <option value="{{ $num }}" {{ (int) request('month') === $num ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="a-form-group" style="margin-bottom:0;">
                <label class="a-label">Year</label>
                <select name="year" class="a-input">
                    <option value="">All years</option>
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ (string) request('year') === (string) $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <div class="a-form-group" style="margin-bottom:0;">
                <label class="a-label">Customer</label>
                <select name="user_id" class="a-input">
                    <option value="">All customers</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ (string) request('user_id') === (string) $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="a-form-group" style="margin-bottom:0;">
                <label class="a-label">Status</label>
                <select name="status" class="a-input">
                    <option value="">All statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="a-btn a-btn-primary"><i class="ti ti-filter"></i> Filter</button>

            @if(request()->hasAny(['date_from', 'date_to', 'month', 'year', 'user_id', 'status']))
                <a href="{{ route('admin.reports.orders') }}" class="a-btn a-btn-ghost">Clear</a>
            @endif
        </form>
    </div>

    <div style="overflow-x:auto;">
    <table class="a-table">
        <thead>
        <tr>
            <th>Reference</th>
            <th>Customer</th>
            <x-a-sortable-th column="status" label="Status" :sort="$sort" :direction="$direction" />
            <x-a-sortable-th column="total" label="Total" :sort="$sort" :direction="$direction" />
            <x-a-sortable-th column="created_at" label="Date" :sort="$sort" :direction="$direction" />
        </tr>
        </thead>
        <tbody>
        @forelse($rows as $row)
            <tr>
                <td>#{{ strtoupper(substr(md5($row->id . $row->created_at), 0, 8)) }}</td>
                <td>{{ $row->user->name ?? '—' }}</td>
                <td><span class="a-badge a-badge-{{ $row->status }}">{{ ucfirst($row->status) }}</span></td>
                <td>₹{{ number_format($row->total, 2) }}</td>
                <td>{{ $row->created_at->format('d M Y') }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="a-empty">No orders found for this filter.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection