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
        }

        body { 
            background: var(--ivory); 
            font-family: 'Inter', system-ui, -apple-system, sans-serif; 
            margin: 0; 
            font-size: 15px;
            color: var(--umber);
            line-height: 1.6;
        }
        
        .staff-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        .staff-sidebar {
            width: 260px; 
            background: var(--umber); 
            position: fixed;
            top: 0; 
            left: 0; 
            height: 100vh; 
            display: flex;
            flex-direction: column; 
            z-index: 20;
            flex-shrink: 0;
        }

        .staff-main { 
            margin-left: 260px; 
            flex: 1;
            display: flex; 
            flex-direction: column; 
            min-width: 0;
            width: calc(100% - 260px);
        }

        .staff-topbar {
            height: 64px; 
            background: var(--champagne);
            border-bottom: 1px solid rgba(196,164,132,0.35);
            padding: 0 2rem;
            display: flex; 
            align-items: center; 
            position: sticky; 
            top: 0; 
            z-index: 10;
            width: 100%;
            box-sizing: border-box;
        }

        .staff-topbar h1 { 
            font-size: 16px; 
            font-weight: 700; 
            color: var(--umber); 
            text-transform: uppercase; 
            letter-spacing: .05em; 
            margin: 0; 
        }

        .staff-content { 
            flex: 1; 
            padding: 2rem; 
            width: 100%; 
            box-sizing: border-box; 
        }

        .sidebar-logo { 
            padding: 24px 20px; 
            border-bottom: 1px solid rgba(255,255,255,0.08); 
        }

        .sidebar-logo span { 
            font-size: 18px; 
            font-weight: 700; 
            color: var(--champagne); 
            letter-spacing: .04em; 
            text-transform: uppercase; 
        }

        .sidebar-logo small { 
            display: block; 
            font-size: 12px; 
            color: var(--mauve); 
            letter-spacing: .1em; 
            margin-top: 2px; 
        }

        .sidebar-nav { 
            flex: 1; 
            padding: 20px 14px; 
            display: flex; 
            flex-direction: column; 
            gap: 4px; 
        }

        .sidebar-nav a {
            display: flex; 
            align-items: center; 
            gap: 12px;
            padding: 12px 16px; 
            border-radius: 10px;
            font-size: 15px; 
            font-weight: 500; 
            color: rgba(247,231,206,0.7);
            text-decoration: none; 
            transition: all .2s;
        }

        .sidebar-nav a i { font-size: 20px; }
        .sidebar-nav a:hover { background: rgba(255,255,255,0.07); color: var(--champagne); }
        .sidebar-nav a.active { background: rgba(128,128,0,0.3); color: #fff; }

        .sidebar-nav .nav-section { 
            font-size: 11px; 
            text-transform: uppercase; 
            letter-spacing: .12em; 
            color: rgba(196,164,132,0.5); 
            padding: 15px 16px 8px; 
        }

        .sidebar-footer { padding: 16px; border-top: 1px solid rgba(255,255,255,0.08); }
        .sidebar-user { display: flex; align-items: center; gap: 12px; padding: 10px; border-radius: 10px; margin-bottom: 8px; }
        .sidebar-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--olive); display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; color: #fff; flex-shrink: 0; }
        .sidebar-user-name { font-size: 14px; font-weight: 600; color: var(--champagne); }
        .sidebar-user-role { font-size: 12px; color: var(--mauve); }

        .sidebar-logout { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            width: 100%; 
            padding: 12px; 
            border-radius: 10px; 
            font-size: 14px; 
            color: rgba(196,164,132,0.7); 
            background: none; 
            border: none; 
            cursor: pointer; 
            transition: all .2s; 
            text-align: left; 
        }

        .sidebar-logout:hover { background: rgba(255,255,255,0.06); color: var(--champagne); }

        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat { background: var(--champagne); border-radius: 16px; padding: 20px; border: 1px solid rgba(196,164,132,0.3); }
        .stat-label { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--mauve); margin-bottom: 8px; }
        .stat-num { font-size: 32px; font-weight: 700; line-height: 1; color: var(--umber); }

        .s-card { background: #fff; border: 1px solid rgba(196,164,132,0.3); border-radius: 16px; overflow: hidden; margin-bottom: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .s-card-head { padding: 16px 20px; border-bottom: 1px solid rgba(196,164,132,0.2); display: flex; align-items: center; justify-content: space-between; background: var(--champagne); }
        .s-card-title { font-size: 14px; font-weight: 700; color: var(--umber); text-transform: uppercase; letter-spacing: .07em; }
        .s-card-link { font-size: 14px; color: var(--olive); text-decoration: none; font-weight: 600; }

        .s-row { padding: 14px 20px; border-bottom: 1px solid rgba(196,164,132,0.12); display: flex; align-items: center; justify-content: space-between; gap: 15px; }
        .s-row-ref { font-size: 15px; font-weight: 700; color: var(--umber); }
        .s-row-meta { font-size: 13px; color: var(--mauve); }

        .s-badge { font-size: 12px; font-weight: 700; padding: 5px 12px; border-radius: 8px; white-space: nowrap; }
        .s-badge-pending   { background:#FEF3C7; color:#92400E; }
        .s-badge-confirmed { background:#DBEAFE; color:#1E40AF; }
        .s-badge-picking   { background:#EDE9FE; color:#5B21B6; }
        .s-badge-packed    { background:#E0E7FF; color:#3730A3; }
        .s-badge-delivered { background:#D1FAE5; color:#065F46; }
        .s-badge-cancelled { background:#FEE2E2; color:#991B1B; }

        .s-open-btn { font-size: 13px; color: var(--olive); text-decoration: none; font-weight: 700; padding: 8px 16px; border: 1.5px solid rgba(128,128,0,0.3); border-radius: 8px; transition: all .2s; }
        .s-open-btn:hover { background: var(--olive); color: #fff; }

        .s-two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .s-low-grid { display: grid; grid-template-columns: 1fr 1fr; }
        .s-low-item { padding: 14px 20px; border-bottom: 1px solid rgba(196,164,132,0.12); border-right: 1px solid rgba(196,164,132,0.12); display: flex; align-items: center; justify-content: space-between; }
        .s-low-name { font-size: 14px; color: var(--umber); font-weight: 700; }
        .s-low-sub  { font-size: 12px; color: var(--mauve); }
        .s-stock-pill { font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 6px; }

        @media(max-width: 992px) {
            .staff-sidebar { width: 100%; height: auto; position: relative; }
            .staff-main { margin-left: 0; width: 100%; }
            .s-two-col, .s-low-grid { grid-template-columns: 1fr; }
            .s-low-item { border-right: none; }
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
            <h1>@yield('page-title', 'Staff Dashboard')</h1>
        </header>
        <main class="staff-content">
            @yield('content')
        </main>
    </div>

</div>

<link rel="stylesheet" href="https://unpkg.com/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
@stack('scripts')
</body>
</html>