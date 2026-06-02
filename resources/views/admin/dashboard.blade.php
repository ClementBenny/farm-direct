@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')

<div class="a-page-head">
    <div>
        <div class="a-page-title">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }}</div>
        <div class="a-page-sub">{{ now()->format('l, j F Y') }} &mdash; Platform overview</div>
    </div>
</div>

<div class="a-stat-grid">
    <div class="a-stat">
        <div>
            <div class="a-stat-label">Total Users</div>
            <div class="a-stat-num">{{ number_format($totalUsers) }}</div>
        </div>
        <div class="a-stat-icon"><i class="ti ti-user"></i></div>
    </div>
    <div class="a-stat">
        <div>
            <div class="a-stat-label">Products</div>
            <div class="a-stat-num">{{ number_format($totalProducts) }}</div>
        </div>
        <div class="a-stat-icon"><i class="ti ti-clipboard-list"></i></div>
    </div>
    <div class="a-stat">
        <div>
            <div class="a-stat-label">Total Orders</div>
            <div class="a-stat-num">{{ number_format($totalOrders) }}</div>
            @if($pendingOrders > 0)
                <div class="a-stat-sub">{{ $pendingOrders }} pending</div>
            @endif
        </div>
        <div class="a-stat-icon"><i class="ti ti-shopping-bag"></i></div>
    </div>
    <div class="a-stat" style="border-style:dashed; opacity:0.55;">
        <div>
            <div class="a-stat-label">Revenue</div>
            <div style="font-size:12px; font-weight:700; color:var(--muted); font-style:italic; margin-top:6px;">Phase 3 only</div>
        </div>
        <div class="a-stat-icon" style="opacity:0.4;"><i class="ti ti-device-desktop-analytics"></i></div>
    </div>
</div>

<div class="a-two-col" style="margin-bottom:1.5rem;">

    <div style="display:flex; flex-direction:column; gap:1.25rem;">

        <div class="a-card">
            <div class="a-card-head"><span class="a-card-title">Users by Role</span></div>
            <div class="a-card-body">
                @php
                    $roleColors = [
                        'admin'    => ['fill' => '#5b21b6', 'badge' => 'a-badge-admin'],
                        'customer' => ['fill' => '#1e40af', 'badge' => 'a-badge-customer'],
                        'shop'     => ['fill' => '#92400e', 'badge' => 'a-badge-shop'],
                        'staff'    => ['fill' => '#065f46', 'badge' => 'a-badge-staff'],
                    ];
                @endphp
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                    @foreach(['admin', 'customer', 'shop', 'staff'] as $role)
                    @php $count = $usersByRole[$role] ?? 0; @endphp
                    <div style="background:var(--bg); border:1px solid var(--border); border-radius:10px; padding:14px 16px;">
                        <div style="font-size:12px; font-weight:600; color:var(--muted); text-transform:capitalize; margin-bottom:6px;">{{ ucfirst($role) }}</div>
                        <div style="font-size:1.6rem; font-weight:800; color:var(--dark); line-height:1;">{{ $count }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="a-card">
            <div class="a-card-head">
                <span class="a-card-title">Recently Joined</span>
                <a href="{{ route('admin.users.index') }}" class="a-card-link">View all &rarr;</a>
            </div>
            <div>
                @forelse($recentUsers as $user)
                <div class="a-row">
                    <div class="a-avatar" style="width:36px; height:36px; font-size:13px; background:var(--bg);">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div style="font-size:13px; font-weight:700; color:var(--dark); line-height:1.2;">{{ $user->name }}</div>
                        <div style="font-size:11px; color:var(--muted);">{{ $user->email }}</div>
                    </div>
                </div>
                @empty
                <div class="a-empty">No users yet.</div>
                @endforelse
            </div>
        </div>

    </div>

    <div style="display:flex; flex-direction:column; gap:1.25rem;">

        <div class="a-card">
            <div class="a-card-head">
                <div>
                    <div class="a-card-title">Stock Alerts</div>
                    @php $lowStock = \App\Models\Product::where('stock', '<=', 10)->orderBy('stock')->get(); @endphp
                    @if($lowStock->isNotEmpty())
                        <div style="font-size:12px; color:var(--muted); margin-top:3px;">{{ $lowStock->count() }} product(s) need attention</div>
                    @endif
                </div>
                @if($lowStock->isNotEmpty())
                    <span class="a-badge" style="background:#fee2e2; color:#991b1b;">{{ $lowStock->count() }} attention required</span>
                @endif
            </div>
            @if($lowStock->isEmpty())
                <div class="a-empty">All stock levels healthy.</div>
            @else
                <table class="a-table">
                    <tbody>
                        @foreach($lowStock as $product)
                        <tr>
                            <td style="font-weight:600;">{{ $product->name }}</td>
                            <td style="font-weight:700; color:{{ $product->stock === 0 ? '#991b1b' : '#92400e' }};">{{ $product->stock }} units</td>
                            <td class="right">
                                <span class="a-pill {{ $product->stock === 0 ? 'a-pill-critical' : 'a-pill-low' }}">
                                    {{ $product->stock === 0 ? 'Out' : 'Low' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>

</div>

<div class="a-card">
    <div class="a-card-head">
        <span class="a-card-title">Recent Orders</span>
        <a href="{{ route('admin.orders.index') }}" class="a-card-link">View all &rarr;</a>
    </div>
    @php $recentOrders = \App\Models\Order::with('user')->latest()->take(8)->get(); @endphp
    @if($recentOrders->isEmpty())
        <div class="a-empty">No orders yet.</div>
    @else
    <table class="a-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer / Time</th>
                <th>Status</th>
                <th class="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentOrders as $order)
            <tr style="cursor:pointer;" onclick="window.location='{{ route('admin.orders.show', $order) }}'">
                <td style="font-weight:700; font-family:monospace; font-size:13px; color:var(--dark);">
                    #{{ strtoupper(substr(md5($order->id . $order->created_at), 0, 8)) }}
                </td>
                <td>
                    <div style="font-weight:600; font-size:13px; color:var(--dark);">{{ $order->user->name ?? 'Unknown' }}</div>
                    <div style="font-size:11px; color:var(--muted);">{{ $order->created_at->diffForHumans() }}</div>
                </td>
                <td><span class="a-badge a-badge-{{ $order->status }}">{{ $order->status }}</span></td>
                <td class="right" style="font-weight:700; font-size:13px;">£{{ number_format($order->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

@endsection