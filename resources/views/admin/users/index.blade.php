@extends('layouts.admin')

@section('page-title', 'Users')

@section('content')

<div class="a-page-head">
    <div>
        <h1 class="a-page-title">Users</h1>
        <p class="a-page-sub">Manage accounts and roles</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="a-btn a-btn-primary">
        <i class="ti ti-plus"></i> Add User
    </a>
</div>

<div class="a-card">
    <div class="a-card-body" style="padding:0">
        <table class="a-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th class="right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td style="font-weight:600; color:var(--dark)">{{ $user->name }}</td>
                    <td style="color:var(--muted)">{{ $user->email }}</td>
                    <td>
                        <span class="a-badge a-badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td style="color:var(--muted)">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="right" style="white-space:nowrap">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="a-btn a-btn-ghost" style="font-size:0.8rem; padding:0.3rem 0.75rem">
                            <i class="ti ti-pencil"></i> Edit
                        </a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                              class="inline" onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.')">
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
                    <td colspan="5">
                        <div class="a-empty">
                            <i class="ti ti-users-off" style="font-size:2rem; margin-bottom:0.5rem; display:block"></i>
                            No users yet.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection