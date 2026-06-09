@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')

<div class="a-page-head">
    <div>
        <div class="a-page-title">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }}</div>
        <div class="a-page-sub">{{ now()->format('l, j F Y') }} &mdash; Platform overview</div>
    </div>
</div>

{{-- Stat strip --}}
<div class="a-stat-grid">
    <div class="a-stat">
        <div>
            <div class="a-stat-label">Total Customers</div>
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
    <div class="a-stat">
        <div>
            <div class="a-stat-label">Revenue</div>
            <div class="a-stat-num" style="font-size:1.4rem;">₹{{ number_format($totalRevenue, 0) }}</div>
            <div class="a-stat-sub">excl. cancelled</div>
        </div>
        <div class="a-stat-icon"><i class="ti ti-device-desktop-analytics"></i></div>
    </div>
</div>

{{-- Sales chart (full width) --}}
<div class="a-card">
    <div class="a-card-head">
        <span class="a-card-title"><i class="ti ti-chart-area"></i> Sales Overview</span>
        <span style="font-size:11px; color:var(--muted)">Last 12 weeks &middot; excl. cancelled</span>
    </div>
    <div class="a-card-body">
        <canvas id="salesChart" height="90"></canvas>
    </div>
</div>

{{-- Three-column row: Users by Role | Recently Joined | Stock Alerts --}}
<div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1.25rem; margin-bottom:1.5rem; align-items:start;">

    <div class="a-card" style="margin-bottom:0">
        <div class="a-card-head"><span class="a-card-title">Users by Role</span></div>
        <div class="a-card-body">
            @php
                $roles = ['customer'=>['ti-user','Customers'],'shop'=>['ti-building-store','Wholesale'],'staff'=>['ti-tools','Staff'],'admin'=>['ti-shield','Admins']];
                $total = max(1, array_sum($usersByRole->toArray()));
            @endphp
            <div style="display:flex; flex-direction:column; gap:0.6rem;">
                @foreach($roles as $role => [$icon, $label])
                @php $count = $usersByRole[$role] ?? 0; @endphp
                <div style="display:flex; align-items:center; gap:0.75rem; padding:0.6rem 0.75rem; background:var(--bg); border:1px solid var(--border); border-radius:8px;">
                    <i class="ti {{ $icon }}" style="font-size:15px; color:var(--muted); width:16px; text-align:center; flex-shrink:0;"></i>
                    <span style="font-size:12px; font-weight:600; color:var(--dark); width:72px; flex-shrink:0;">{{ $label }}</span>
                    <div style="flex:1; height:5px; background:var(--border); border-radius:99px; overflow:hidden;">
                        <div style="height:100%; width:{{ round(($count/$total)*100) }}%; background:var(--accent); border-radius:99px;"></div>
                    </div>
                    <span style="font-size:13px; font-weight:800; color:var(--dark); min-width:24px; text-align:right;">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="a-card" style="margin-bottom:0">
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

    <div class="a-card" style="margin-bottom:0">
        <div class="a-card-head">
            <div>
                <div class="a-card-title">Stock Alerts</div>
                @php $lowStock = \App\Models\Product::where('stock', '<=', 10)->orderBy('stock')->get(); @endphp
                @if($lowStock->isNotEmpty())
                    <div style="font-size:12px; color:var(--muted); margin-top:3px;">{{ $lowStock->count() }} product(s) need attention</div>
                @endif
            </div>
            @if($lowStock->isNotEmpty())
                <span class="a-badge" style="background:#fee2e2; color:#991b1b;">{{ $lowStock->count() }}</span>
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

{{-- Recent Orders (full width) --}}
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
                    #00{{ $order->id  }}
                </td>
                <td>
                    <div style="font-weight:600; font-size:13px; color:var(--dark);">{{ $order->user->name ?? 'Unknown' }}</div>
                    <div style="font-size:11px; color:var(--muted);">{{ $order->created_at->diffForHumans() }}</div>
                </td>
                <td><span class="a-badge a-badge-{{ $order->status }}">{{ $order->status }}</span></td>
                <td class="right" style="font-weight:700; font-size:13px;">₹{{ number_format($order->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const raw = @json($salesData);
    const monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const labels = raw.map(r => {
        const month = monthNames[parseInt(r.month_num) - 1];
        const weekOfMonth = Math.ceil(parseInt(r.day) / 7);
        return 'W' + weekOfMonth + ' ' + month;
    });
    const revenue = raw.map(r => parseFloat(r.revenue ?? 0));
    const orders  = raw.map(r => parseInt(r.order_count ?? 0));

    new Chart(document.getElementById('salesChart').getContext('2d'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Revenue (₹)',
                    data: revenue,
                    yAxisID: 'yRevenue',
                    fill: true,
                    backgroundColor: 'rgba(92,74,58,0.10)',
                    borderColor: 'rgba(92,74,58,0.75)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(92,74,58,0.9)',
                    pointRadius: 4,
                    tension: 0.4,
                },
                {
                    label: 'Orders',
                    data: orders,
                    yAxisID: 'yOrders',
                    fill: true,
                    backgroundColor: 'rgba(128,128,0,0.08)',
                    borderColor: 'rgba(128,128,0,0.65)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(128,128,0,0.9)',
                    pointRadius: 4,
                    tension: 0.4,
                },
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: 'top', align: 'end',
                    labels: { font: { size: 11, weight: '700' }, color: '#5C4A3A', boxWidth: 12, padding: 16 }
                },
                tooltip: {
                    backgroundColor: '#2C2018',
                    titleColor: '#F7E7CE',
                    bodyColor: '#C4A484',
                    padding: 10,
                    callbacks: {
                        label: ctx => ctx.datasetIndex === 0
                            ? ` ₹${ctx.parsed.y.toLocaleString('en-IN', { minimumFractionDigits: 2 })}`
                            : ` ${ctx.parsed.y} orders`
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(224,216,206,0.5)' },
                    ticks: { font: { size: 11 }, color: '#9A8F85' }
                },
                yRevenue: {
                    position: 'left',
                    grid: { color: 'rgba(224,216,206,0.5)' },
                    ticks: {
                        font: { size: 11 }, color: '#5C4A3A',
                        callback: v => '₹' + v.toLocaleString('en-IN')
                    }
                },
                yOrders: {
                    position: 'right',
                    min: 0,
                    grid: { drawOnChartArea: false },
                    ticks: { font: { size: 11 }, color: '#808000' }
                }
            }
        }
    });
</script>
@endpush