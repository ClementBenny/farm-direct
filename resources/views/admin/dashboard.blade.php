@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
{{-- Main Wrapper --}}
<div class="w-full">
    
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Platform overview and system metrics.</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        
        {{-- Users --}}
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalUsers) }}</p>
                </div>
                <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
            </div>
        </div>

        {{-- Products --}}
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Products</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalProducts) }}</p>
                </div>
                <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
            </div>
        </div>

        {{-- Orders --}}
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Orders</p>
                    <div class="flex items-baseline gap-2">
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalOrders) }}</p>
                        <span class="text-xs text-amber-600 font-medium">{{ $pendingOrders }} pending</span>
                    </div>
                </div>
                <div class="p-2 bg-amber-50 rounded-lg text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
            </div>
        </div>

        {{-- Revenue --}}
        <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 shadow-sm border-dashed">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Revenue</p>
            <p class="text-sm italic text-gray-400 mt-2 text-center uppercase tracking-widest font-bold">Phase 3 Only</p>
        </div>

    </div>

    {{-- Lower Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        {{-- Role Distribution --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-700 uppercase">Users by Role</h3>
            </div>
            <div class="p-5 space-y-4">
                @php
                    $roleConfig = [
                        'admin'    => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'bar' => 'bg-purple-600'],
                        'customer' => ['bg' => 'bg-blue-100',   'text' => 'text-blue-700',   'bar' => 'bg-blue-600'],
                        'shop'     => ['bg' => 'bg-amber-100',  'text' => 'text-amber-700',  'bar' => 'bg-amber-600'],
                        'staff'    => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'bar' => 'bg-green-600'],
                    ];
                @endphp

                @foreach(['admin', 'customer', 'shop', 'staff'] as $role)
                @php
                    $count = $usersByRole[$role] ?? 0;
                    $percent = $totalUsers > 0 ? ($count / $totalUsers) * 100 : 0;
                    $cfg = $roleConfig[$role];
                @endphp
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $cfg['bg'] }} {{ $cfg['text'] }}">{{ $role }}</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $count }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="{{ $cfg['bar'] }} h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Users Table --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-sm font-bold text-gray-700 uppercase">Recently Joined</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">View All &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">User</th>
                            <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Role</th>
                            <th class="px-5 py-3 text-right text-[10px] font-bold text-gray-400 uppercase tracking-wider">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentUsers as $user)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center font-bold text-xs text-gray-500 border">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800 leading-none">{{ $user->name }}</p>
                                        <p class="text-[11px] text-gray-400 mt-0.5">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $roleConfig[$user->role]['bg'] ?? 'bg-gray-100' }} {{ $roleConfig[$user->role]['text'] ?? 'text-gray-600' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right text-xs text-gray-400">
                                {{ $user->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection