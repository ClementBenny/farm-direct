<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Farm Direct')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

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
            font-family: 'Jost', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .auth-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2.5rem 2.25rem;
            width: 100%;
            max-width: 400px;
        }

        .auth-brand { text-align: center; margin-bottom: 2rem; }
        .auth-brand-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--dark);
            letter-spacing: 0.02em;
            line-height: 1;
        }
        .auth-brand-sub {
            font-size: 12px;
            color: var(--muted);
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-top: 4px;
        }
        .auth-divider {
            width: 32px;
            height: 1px;
            background: var(--border);
            margin: 1rem auto;
        }
        .auth-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--accent);
            text-align: center;
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 6px;
        }
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 0.65rem 0.9rem;
            font-family: 'Jost', sans-serif;
            font-size: 14px;
            color: var(--dark);
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            outline: none;
            transition: border-color 0.2s;
        }
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            border-color: var(--mauve);
            background: var(--surface);
        }

        .field { margin-bottom: 1.1rem; }

        .auth-error {
            font-size: 12px;
            color: #b91c1c;
            margin-top: 4px;
        }
        .auth-status {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            font-size: 13px;
            padding: 0.6rem 0.9rem;
            border-radius: 8px;
            margin-bottom: 1.25rem;
        }

        .auth-remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 1.5rem;
        }
        .auth-remember input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: var(--accent);
            cursor: pointer;
        }
        .auth-remember span { font-size: 13px; color: var(--muted); }

        .auth-btn {
            width: 100%;
            padding: 0.75rem;
            background: var(--accent);
            color: var(--champagne);
            font-family: 'Jost', sans-serif;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .auth-btn:hover { background: var(--dark); }

        .auth-link {
            display: block;
            text-align: center;
            margin-top: 1.1rem;
            font-size: 12px;
            color: var(--muted);
            text-decoration: none;
            letter-spacing: 0.04em;
        }
        .auth-link:hover { color: var(--accent); }

        .auth-hint {
            font-size: 13px;
            color: var(--muted);
            text-align: center;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="auth-brand">
        <a href="{{ url('/') }}" style="text-decoration:none">
            <div class="auth-brand-name">Farm Direct</div>
            <div class="auth-brand-sub">Kerala &middot; Est. 2026</div>
        </a>
        <div class="auth-divider"></div>
    </div>

    @yield('content')
</div>

</body>
</html>