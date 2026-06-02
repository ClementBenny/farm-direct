<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --ivory:     #FFFBF0;
            --champagne: #F7E7CE;
            --mauve:     #C4A484;
            --olive:     #808000;
            --umber:     #4B3621;
            --bg:        #F5F2EE;
            --surface:   #FFFFFF;
            --border:    #E0D8CE;
            --muted:     #9A8F85;
            --accent:    #5C4A3A;
            --dark:      #2C2018;
        }

        body {
            background: var(--bg);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            margin: 0; font-size: 15px;
            color: var(--dark); line-height: 1.6;
        }

        .admin-wrapper { display: flex; min-height: 100vh; width: 100%; }

        .admin-sidebar {
            width: 260px; background: var(--dark);
            position: fixed; top: 0; left: 0; height: 100vh;
            display: flex; flex-direction: column;
            z-index: 20; flex-shrink: 0;
        }

        .admin-main {
            margin-left: 260px; flex: 1;
            display: flex; flex-direction: column;
            min-width: 0; width: calc(100% - 260px);
        }

        .admin-topbar {
            height: 64px; background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            display: flex; align-items: center; gap: 0.4rem;
            position: sticky; top: 0; z-index: 10;
            width: 100%; box-sizing: border-box;
        }

        .admin-topbar-breadcrumb { font-size: 13px; color: var(--muted); font-weight: 500; }
        .admin-topbar-title { font-size: 15px; font-weight: 700; color: var(--dark); text-transform: uppercase; letter-spacing: .05em; }

        .admin-content { flex: 1; padding: 2rem; width: 100%; box-sizing: border-box; }

        .sidebar-logo { padding: 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.06); }
        .sidebar-logo span { font-size: 18px; font-weight: 700; color: var(--champagne); letter-spacing: .04em; text-transform: uppercase; }
        .sidebar-logo small { display: block; font-size: 11px; color: var(--muted); letter-spacing: .12em; text-transform: uppercase; margin-top: 3px; }

        .sidebar-nav { flex: 1; padding: 20px 14px; display: flex; flex-direction: column; gap: 2px; overflow-y: auto; }

        .sidebar-nav a {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 16px; border-radius: 8px;
            font-size: 14px; font-weight: 500;
            color: rgba(224,216,206,0.65);
            text-decoration: none; transition: all .18s;
        }

        .sidebar-nav a i { font-size: 18px; flex-shrink: 0; }
        .sidebar-nav a:hover { background: rgba(255,255,255,0.06); color: var(--champagne); }
        .sidebar-nav a.active { background: var(--accent); color: #fff; }

        .nav-section {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .14em; color: rgba(154,143,133,0.55);
            padding: 16px 16px 6px;
        }

        .sidebar-footer { padding: 14px; border-top: 1px solid rgba(255,255,255,0.06); }

        .sidebar-user { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; margin-bottom: 6px; }

        .sidebar-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--accent); color: var(--champagne);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; flex-shrink: 0;
        }

        .sidebar-user-name { font-size: 13px; font-weight: 600; color: var(--champagne); line-height: 1.2; }
        .sidebar-user-role { font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: .07em; }

        .sidebar-logout {
            display: flex; align-items: center; gap: 10px;
            width: 100%; padding: 10px 14px; border-radius: 8px;
            font-size: 13px; font-weight: 500; color: rgba(154,143,133,0.8);
            background: none; border: none; cursor: pointer;
            transition: all .18s; text-align: left;
        }

        .sidebar-logout i { font-size: 17px; }
        .sidebar-logout:hover { background: rgba(180,40,40,0.15); color: #fca5a5; }

        .a-page-head { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.75rem; }
        .a-page-title { font-size: 1.4rem; font-weight: 800; color: var(--dark); line-height: 1; }
        .a-page-sub { font-size: 0.78rem; color: var(--muted); margin-top: 4px; }

        .a-stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.75rem; }

        .a-stat {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 12px; padding: 18px 20px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 1px 3px rgba(44,32,24,0.05);
        }

        .a-stat-icon {
            width: 40px; height: 40px; border-radius: 10px;
            background: var(--bg); color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0; border: 1px solid var(--border);
        }

        .a-stat-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .09em; color: var(--muted); margin-bottom: 4px; }
        .a-stat-num { font-size: 1.75rem; font-weight: 800; color: var(--dark); line-height: 1; }
        .a-stat-sub { font-size: 11px; color: var(--olive); font-weight: 600; margin-top: 3px; }

        .a-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 12px; overflow: hidden;
            box-shadow: 0 1px 3px rgba(44,32,24,0.05); margin-bottom: 1.5rem;
        }

        .a-card-head {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 20px; border-bottom: 1px solid var(--border); background: var(--bg);
        }

        .a-card-title { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: var(--dark); }
        .a-card-link { font-size: 12px; font-weight: 700; color: var(--olive); text-decoration: none; }
        .a-card-link:hover { text-decoration: underline; }
        .a-card-body { padding: 1.25rem; }

        .a-two-col { display: grid; grid-template-columns: 1fr 2fr; gap: 1.25rem; align-items: start; }
        @media (max-width: 900px) { .a-two-col { grid-template-columns: 1fr; } }

        .a-table { width: 100%; border-collapse: collapse; }
        .a-table thead tr { background: var(--bg); border-bottom: 1px solid var(--border); }
        .a-table th { padding: 10px 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: var(--muted); text-align: left; }
        .a-table th.right, .a-table td.right { text-align: right; }
        .a-table tbody tr { border-bottom: 1px solid var(--border); transition: background .12s; }
        .a-table tbody tr:last-child { border-bottom: none; }
        .a-table tbody tr:hover { background: var(--bg); }
        .a-table td { padding: 12px 20px; font-size: 13px; color: var(--dark); }
        .a-table tfoot tr { background: var(--bg); border-top: 1px solid var(--border); }
        .a-table tfoot td { padding: 10px 20px; font-size: 12px; font-weight: 700; color: var(--dark); }

        .a-row { display: flex; align-items: center; gap: 12px; padding: 13px 20px; border-bottom: 1px solid var(--border); text-decoration: none; color: inherit; transition: background .12s; }
        .a-row:last-child { border-bottom: none; }
        .a-row:hover { background: var(--bg); }
        .a-row-ref { font-size: 13px; font-weight: 700; color: var(--dark); }
        .a-row-meta { font-size: 12px; color: var(--muted); margin-top: 1px; }
        .a-row-right { margin-left: auto; text-align: right; }

        .a-badge { display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 6px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; white-space: nowrap; }
        .a-badge-pending   { background: #fef3c7; color: #92400e; }
        .a-badge-confirmed { background: #dbeafe; color: #1e40af; }
        .a-badge-picking   { background: #ede9fe; color: #5b21b6; }
        .a-badge-packed    { background: #e0e7ff; color: #3730a3; }
        .a-badge-delivered { background: #d1fae5; color: #065f46; }
        .a-badge-cancelled { background: #fee2e2; color: #991b1b; }
        .a-badge-admin     { background: #ede9fe; color: #5b21b6; }
        .a-badge-customer  { background: #dbeafe; color: #1e40af; }
        .a-badge-shop      { background: #fef3c7; color: #92400e; }
        .a-badge-staff     { background: #d1fae5; color: #065f46; }

        .a-role-bar { margin-bottom: 14px; }
        .a-role-bar-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
        .a-role-bar-track { width: 100%; background: var(--border); border-radius: 9999px; height: 5px; overflow: hidden; }
        .a-role-bar-fill { height: 100%; border-radius: 9999px; }

        .a-avatar { width: 32px; height: 32px; border-radius: 50%; background: var(--bg); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; flex-shrink: 0; border: 1px solid var(--border); }

        .a-btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: none; border: none; transition: opacity .15s; }
        .a-btn:hover { opacity: 0.85; }
        .a-btn-primary { background: var(--dark);  color: var(--champagne); }
        .a-btn-olive   { background: var(--olive); color: #fff; }
        .a-btn-danger  { background: #dc2626;       color: #fff; }
        .a-btn-ghost   { background: transparent; color: var(--dark); border: 1px solid var(--border); }

        .a-label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--dark); margin-bottom: 5px; }
        .a-input { width: 100%; padding: 9px 12px; border: 1px solid var(--border); border-radius: 8px; background: var(--surface); color: var(--dark); font-size: 13px; outline: none; transition: border-color .15s; box-sizing: border-box; }
        .a-input:focus { border-color: var(--accent); }
        .a-form-group { margin-bottom: 1rem; }

        .a-pill { padding: 3px 9px; border-radius: 6px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; }
        .a-pill-critical { background: #fee2e2; color: #991b1b; }
        .a-pill-low      { background: #fef3c7; color: #92400e; }
        .a-pill-good     { background: #d1fae5; color: #065f46; }

        .a-empty { padding: 2.5rem 1rem; text-align: center; color: var(--muted); font-size: 13px; font-style: italic; }
        .a-footer-note { padding: 10px 20px; font-size: 11px; color: var(--muted); background: var(--bg); border-top: 1px solid var(--border); text-align: right; }
        .a-divider { border: none; border-top: 1px solid var(--border); margin: 1.25rem 0; }

        .a-toast { position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 999; background: var(--dark); color: var(--champagne); padding: 12px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; box-shadow: 0 4px 16px rgba(0,0,0,0.18); display: flex; align-items: center; gap: 8px; }
    </style>
    @stack('styles')
</head>
<body>

<div class="admin-wrapper">

    <aside class="admin-sidebar">
        <div class="sidebar-logo">
            <span>Farm Direct</span>
            <small>Admin Panel</small>
        </div>

        <nav class="sidebar-nav">
            <span class="nav-section">Overview</span>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="ti ti-layout-dashboard"></i> Dashboard
            </a>

            <span class="nav-section">Catalogue</span>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="ti ti-tags"></i> Categories
            </a>
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="ti ti-plant-2"></i> Products
            </a>

            <span class="nav-section">Operations</span>
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="ti ti-shopping-cart"></i> Orders
            </a>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="ti ti-users"></i> Users
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                    <div class="sidebar-user-role">Administrator</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout">
                    <i class="ti ti-logout"></i> Sign out
                </button>
            </form>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <span class="admin-topbar-breadcrumb">Admin /</span>
            <span class="admin-topbar-title">@yield('page-title', 'Dashboard')</span>
        </header>

        <main class="admin-content">
            <div style="max-width: 72rem; margin: 0 auto;">
                @include('partials.flash')
                @yield('content')
            </div>
        </main>
    </div>

</div>

<link rel="stylesheet" href="https://unpkg.com/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

<script>
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) window.location.reload();
    });
</script>

@stack('scripts')
</body>
</html>