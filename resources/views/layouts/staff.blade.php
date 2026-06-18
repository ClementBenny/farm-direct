<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — Staff</title>
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

        .staff-wrapper { display: flex; min-height: 100vh; width: 100%; }

        .staff-sidebar {
            width: 260px; background: var(--dark);
            position: fixed; top: 0; left: 0; height: 100vh;
            display: flex; flex-direction: column;
            z-index: 20; flex-shrink: 0;
        }

        .staff-main {
            margin-left: 260px; flex: 1;
            display: flex; flex-direction: column;
            min-width: 0; width: calc(100% - 260px);
        }

        .staff-topbar {
            height: 64px; background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            display: flex; align-items: center; gap: 0.4rem;
            position: sticky; top: 0; z-index: 10;
            width: 100%; box-sizing: border-box;
        }

        .staff-topbar-breadcrumb { font-size: 13px; color: var(--muted); font-weight: 500; }
        .staff-topbar-title { font-size: 15px; font-weight: 700; color: var(--dark); text-transform: uppercase; letter-spacing: .05em; }

        .staff-content { flex: 1; padding: 2rem; width: 100%; box-sizing: border-box; }

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

        /* page head */
        .s-page-head { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.75rem; }
        .s-page-title { font-size: 1.4rem; font-weight: 800; color: var(--dark); line-height: 1; }
        .s-page-sub { font-size: 0.78rem; color: var(--muted); margin-top: 4px; }

        /* stat cards */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.75rem; }

        .stat {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 12px; padding: 18px 20px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 1px 3px rgba(44,32,24,0.05);
        }

        .stat-icon {
            width: 40px; height: 40px; border-radius: 10px;
            background: var(--bg); color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0; border: 1px solid var(--border);
        }

        .stat-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .09em; color: var(--muted); margin-bottom: 4px; }
        .stat-num { font-size: 1.75rem; font-weight: 800; color: var(--dark); line-height: 1; }
        .stat-sub { font-size: 11px; color: var(--olive); font-weight: 600; margin-top: 3px; }

        /* cards */
        .s-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 12px; overflow: hidden;
            box-shadow: 0 1px 3px rgba(44,32,24,0.05); margin-bottom: 1.5rem;
        }

        .s-card-head {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 20px; border-bottom: 1px solid var(--border); background: var(--bg);
        }

        .s-card-title { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: var(--dark); }
        .s-card-link { font-size: 12px; font-weight: 700; color: var(--olive); text-decoration: none; }
        .s-card-link:hover { text-decoration: underline; }
        .s-card-body { padding: 1.25rem; }

        .s-two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; align-items: start; }
        @media (max-width: 900px) { .s-two-col { grid-template-columns: 1fr; } }

        /* table */
        .s-table { width: 100%; border-collapse: collapse; }
        .s-table thead tr { background: var(--bg); border-bottom: 1px solid var(--border); }
        .s-table th { padding: 10px 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: var(--muted); text-align: left; }
        .s-table th.right, .s-table td.right { text-align: right; }
        .s-table tbody tr { border-bottom: 1px solid var(--border); transition: background .12s; }
        .s-table tbody tr:last-child { border-bottom: none; }
        .s-table tbody tr:hover { background: var(--bg); }
        .s-table td { padding: 12px 20px; font-size: 13px; color: var(--dark); }

        /* row list (orders etc.) */
        .s-row { display: flex; align-items: center; gap: 12px; padding: 13px 20px; border-bottom: 1px solid var(--border); text-decoration: none; color: inherit; transition: background .12s; }
        .s-row:last-child { border-bottom: none; }
        .s-row:hover { background: var(--bg); }
        .s-row-ref { font-size: 13px; font-weight: 700; color: var(--dark); }
        .s-row-meta { font-size: 12px; color: var(--muted); margin-top: 1px; }
        .s-row-right { margin-left: auto; text-align: right; }

        /* badges — values unchanged, already matched the admin palette */
        .s-badge { display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 6px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; white-space: nowrap; }
        .s-badge-pending   { background: #fef3c7; color: #92400e; }
        .s-badge-confirmed { background: #dbeafe; color: #1e40af; }
        .s-badge-picking   { background: #ede9fe; color: #5b21b6; }
        .s-badge-packed    { background: #e0e7ff; color: #3730a3; }
        .s-badge-delivered { background: #d1fae5; color: #065f46; }
        .s-badge-cancelled { background: #fee2e2; color: #991b1b; }

        .s-avatar { width: 32px; height: 32px; border-radius: 50%; background: var(--bg); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; flex-shrink: 0; border: 1px solid var(--border); }

        /* buttons */
        .s-btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: none; border: none; transition: opacity .15s; }
        .s-btn:hover { opacity: 0.85; }
        .s-btn-primary { background: var(--dark);  color: var(--champagne); }
        .s-btn-olive   { background: var(--olive); color: #fff; }
        .s-btn-danger  { background: #dc2626;       color: #fff; }
        .s-btn-ghost   { background: transparent; color: var(--dark); border: 1px solid var(--border); }

        .s-open-btn { font-size: 12px; font-weight: 700; color: var(--olive); text-decoration: none; padding: 8px 16px; border: 1px solid var(--border); border-radius: 8px; transition: all .15s; }
        .s-open-btn:hover { background: var(--olive); color: #fff; border-color: var(--olive); }

        /* forms */
        .s-label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--dark); margin-bottom: 5px; }
        .s-input { width: 100%; padding: 9px 12px; border: 1px solid var(--border); border-radius: 8px; background: var(--surface); color: var(--dark); font-size: 13px; outline: none; transition: border-color .15s; box-sizing: border-box; }
        .s-input:focus { border-color: var(--accent); }
        .s-form-group { margin-bottom: 1rem; }

        /* stock level pill */
        .s-stock-pill { display: inline-flex; align-items: center; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; padding: 3px 9px; border-radius: 6px; }
        .s-stock-pill-critical { background: #fee2e2; color: #991b1b; }
        .s-stock-pill-low      { background: #fef3c7; color: #92400e; }
        .s-stock-pill-good     { background: #d1fae5; color: #065f46; }

        .s-low-grid { display: grid; grid-template-columns: 1fr 1fr; }
        .s-low-item { padding: 14px 20px; border-bottom: 1px solid var(--border); border-right: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .s-low-item:nth-child(2n) { border-right: none; }
        .s-low-name { font-size: 13px; color: var(--dark); font-weight: 700; }
        .s-low-sub  { font-size: 11px; color: var(--muted); }
        @media (max-width: 900px) { .s-low-grid { grid-template-columns: 1fr; } .s-low-item { border-right: none; } }

        .s-empty { padding: 2.5rem 1rem; text-align: center; color: var(--muted); font-size: 13px; font-style: italic; }
        .s-footer-note { padding: 10px 20px; font-size: 11px; color: var(--muted); background: var(--bg); border-top: 1px solid var(--border); display: flex; justify-content: space-between; }
        .s-footer-note strong { color: var(--dark); font-weight: 700; }
        .s-divider { border: none; border-top: 1px solid var(--border); margin: 1.25rem 0; }

        .s-toast { position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 999; background: var(--dark); color: var(--champagne); padding: 12px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; box-shadow: 0 4px 16px rgba(0,0,0,0.18); display: flex; align-items: center; gap: 8px; }

        @media (max-width: 992px) {
            .staff-sidebar { width: 100%; height: auto; position: relative; }
            .staff-main { margin-left: 0; width: 100%; }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="staff-wrapper">

    <aside class="staff-sidebar">
        <div class="sidebar-logo">
            <span>Farm Direct</span>
            <small>Staff Portal</small>
        </div>

        <nav class="sidebar-nav">
            <span class="nav-section">Main Menu</span>
            <a href="{{ route('staff.dashboard') }}" class="{{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                <i class="ti ti-layout-dashboard"></i> Dashboard
            </a>
            <a href="{{ route('staff.orders') }}" class="{{ request()->routeIs('staff.orders*') ? 'active' : '' }}">
                <i class="ti ti-clipboard-list"></i> Orders
            </a>
            <a href="{{ route('staff.stock') }}" class="{{ request()->routeIs('staff.stock*') ? 'active' : '' }}">
                <i class="ti ti-package"></i> Stock Inventory
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                    <div class="sidebar-user-role">Staff Member</div>
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

    <div class="staff-main">
        <header class="staff-topbar">
            <span class="staff-topbar-breadcrumb">Staff /</span>
            <span class="staff-topbar-title">@yield('page-title', 'Dashboard')</span>
        </header>

        <main class="staff-content">
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