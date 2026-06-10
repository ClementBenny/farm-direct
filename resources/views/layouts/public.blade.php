<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Farm Direct')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400;1,600&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --ivory:    #FFFBF0;
            --champagne:#F7E7CE;
            --mauve:    #C4A484;
            --olive:    #808000;
            --umber:    #4B3621;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            background: var(--ivory);
            color: var(--umber);
            font-family: 'Jost', sans-serif;
            font-weight: 300;
            line-height: 1.7;
            overflow-x: hidden;
        }
        em { font-family: 'Cormorant Garamond', serif; font-style: italic; font-weight: 400; }

        /* ── NAV ── */
        .nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 48px;
            background: rgba(255,251,240,0.9);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(196,164,132,0.2);
            transition: padding 0.3s;
        }
        .nav-logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 22px; font-weight: 600;
            color: var(--umber); text-decoration: none;
            display: flex; align-items: center; gap: 10px;
        }
        .nav-logo-leaf {
            width: 28px; height: 28px;
            background: var(--olive);
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            opacity: 0.85;
        }
        .nav-right { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
        .nav-link {
            font-size: 13px; letter-spacing: 0.05em;
            color: var(--mauve); text-decoration: none;
            transition: color 0.2s; white-space: nowrap;
        }
        .nav-link:hover { color: var(--umber); }
        .nav-link-cart { position: relative; display: flex; align-items: center; gap: 5px; }
        .nav-cart-badge {
            background: var(--umber); color: var(--ivory);
            font-size: 10px; font-weight: 500;
            border-radius: 999px; padding: 1px 6px; line-height: 1.5;
        }
        .nav-role-pill {
            font-size: 10px; letter-spacing: 0.1em; text-transform: uppercase;
            padding: 3px 10px; border-radius: 999px;
            font-weight: 500; white-space: nowrap;
        }
        .nav-role-pill--wholesale { background: rgba(196,164,132,0.2); color: var(--umber); border: 1px solid rgba(75,54,33,0.2); }
        .nav-role-pill--customer  { background: rgba(196,164,132,0.2); color: var(--umber); border: 1px solid rgba(196,164,132,0.4); }
        .nav-divider { width: 1px; height: 18px; background: rgba(196,164,132,0.35); }
        .nav-btn {
            font-size: 13px; letter-spacing: 0.08em;
            padding: 8px 20px; border-radius: 999px; text-decoration: none;
            white-space: nowrap; transition: background 0.2s, color 0.2s, border-color 0.2s;
            cursor: pointer; font-family: 'Jost', sans-serif; font-weight: 300;
        }
        .nav-btn--outline { border: 1px solid var(--mauve); color: var(--umber); background: transparent; }
        .nav-btn--outline:hover { background: var(--umber); color: var(--ivory); border-color: var(--umber); }
        .nav-btn--solid { background: var(--umber); color: var(--ivory); border: 1px solid var(--umber); }
        .nav-btn--solid:hover { background: var(--olive); border-color: var(--olive); }
        .nav-greeting { font-size: 12px; color: var(--mauve); letter-spacing: 0.03em; }
        .nav-greeting strong { color: var(--umber); font-weight: 500; }
        .nav-hamburger {
            display: none; flex-direction: column; gap: 5px;
            cursor: pointer; padding: 4px; background: none; border: none;
        }
        .nav-hamburger span {
            display: block; width: 22px; height: 1.5px;
            background: var(--umber); border-radius: 2px;
            transition: transform 0.25s, opacity 0.25s;
        }
        .nav-mobile { display: none; }

        @media (max-width: 768px) {
            .nav { padding: 16px 24px; }
            .nav-right { display: none; }
            .nav-hamburger { display: flex; }
            .nav-mobile {
                position: fixed; top: 60px; left: 0; right: 0; z-index: 99;
                background: rgba(255,251,240,0.97);
                backdrop-filter: blur(14px);
                border-bottom: 1px solid rgba(196,164,132,0.2);
                padding: 24px;
                display: flex; flex-direction: column; gap: 16px;
                transform: translateY(-110%);
                transition: transform 0.3s ease;
            }
            .nav-mobile.is-open { transform: translateY(0); }
            .nav-mobile .nav-link, .nav-mobile .nav-btn { font-size: 15px; }
            .nav-mobile .nav-btn { text-align: center; }
        }

        /* ── GLOBAL BUTTONS ── */
        .btn-primary {
            display: inline-block; background: var(--umber); color: var(--ivory);
            font-family: 'Jost', sans-serif; font-size: 13px; font-weight: 400;
            letter-spacing: 0.1em; text-transform: uppercase;
            padding: 14px 32px; border-radius: 999px; text-decoration: none;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-primary:hover { background: var(--olive); transform: translateY(-1px); }
        .btn-primary--dark { background: var(--ivory); color: var(--umber); }
        .btn-primary--dark:hover { background: var(--champagne); }
        .btn-ghost {
            display: inline-block; font-size: 13px; letter-spacing: 0.08em;
            color: var(--mauve); text-decoration: none;
            border-bottom: 1px solid rgba(196,164,132,0.4); padding-bottom: 2px;
            transition: color 0.2s, border-color 0.2s;
        }
        .btn-ghost:hover { color: var(--umber); border-color: var(--umber); }

        /* ── GLOBAL TYPOGRAPHY ── */
        .section-label {
            font-size: 11px; font-weight: 500; letter-spacing: 0.18em;
            text-transform: uppercase; color: var(--olive); margin-bottom: 12px;
        }
        .section-title {
            font-family: 'Cormorant Garamond', serif; font-size: 42px; font-weight: 600;
            color: var(--umber); line-height: 1.15; margin-bottom: 20px;
        }

        /* ── SHARED PAGE STYLES ── */
        .page-wrap {
            min-height: 100vh;
            padding: 120px 48px 80px;
            max-width: 900px;
            margin: 0 auto;
            background: var(--ivory);
        }
        .page-heading {
            font-family: 'Cormorant Garamond', serif;
            font-size: 56px; font-weight: 600;
            color: var(--umber); line-height: 1.1;
            margin-bottom: 6px;
        }
        .page-sub {
            font-size: 16px; color: var(--umber);
            opacity: 0.6; margin-bottom: 48px; letter-spacing: 0.02em;
        }

        /* fd-card */
        .fd-card {
            background: var(--champagne);
            border: 1.5px solid rgba(75,54,33,0.18);
            border-radius: 20px;
            padding: 40px 44px;
            margin-bottom: 20px;
            transition: box-shadow 0.25s, transform 0.25s;
        }
        .fd-card:hover {
            box-shadow: 0 12px 40px rgba(75,54,33,0.12);
            transform: translateY(-2px);
        }
        .fd-card--flush { padding: 0; overflow: hidden; }

        .fd-card-label {
            font-size: 11px; font-weight: 500; letter-spacing: 0.2em;
            text-transform: uppercase; color: var(--olive);
            margin-bottom: 28px;
            display: flex; align-items: center; gap: 8px;
        }
        .fd-card-label::after {
            content: ''; flex: 1; height: 1px; background: rgba(75,54,33,0.15);
        }

        .fd-divider { height: 1px; background: rgba(75,54,33,0.15); margin-bottom: 28px; }

        /* fd-link */
        .fd-link {
            font-size: 14px; font-weight: 500; letter-spacing: 0.06em;
            color: var(--umber); text-decoration: none;
            border-bottom: 1.5px solid var(--umber); padding-bottom: 1px;
            transition: color 0.2s, border-color 0.2s; white-space: nowrap;
        }
        .fd-link:hover { color: var(--olive); border-color: var(--olive); }

        /* back-link */
        .back-link {
            display: inline-flex; align-items: center; gap: 7px;
            font-size: 14px; letter-spacing: 0.05em;
            color: var(--mauve); text-decoration: none;
            margin-bottom: 32px; transition: color 0.2s;
        }
        .back-link:hover { color: var(--umber); }

        /* status badges */
        .status-badge {
            font-size: 11px; font-weight: 600; letter-spacing: 0.1em;
            text-transform: uppercase; padding: 6px 16px;
            border-radius: 999px; white-space: nowrap;
        }
        .status-pending   { background: rgba(75,54,33,0.12);  color: var(--umber); border: 1.5px solid rgba(75,54,33,0.3); }
        .status-confirmed { background: rgba(128,128,0,0.15); color: #4a5a00;      border: 1.5px solid rgba(128,128,0,0.4); }
        .status-picking   { background: rgba(75,54,33,0.18);  color: var(--umber); border: 1.5px solid rgba(75,54,33,0.4); }
        .status-packed    { background: rgba(128,128,0,0.2);  color: #3d4d00;      border: 1.5px solid rgba(128,128,0,0.5); }
        .status-delivered { background: var(--olive);         color: #fff;         border: 1.5px solid var(--olive); }
        .status-cancelled { background: rgba(140,40,40,0.12); color: #8c2828;      border: 1.5px solid rgba(140,40,40,0.3); }

        /* cancelled notice */
        .cancelled-notice {
            display: flex; align-items: center; gap: 12px;
            padding: 16px 20px;
            background: rgba(140,40,40,0.08);
            border: 1.5px solid rgba(140,40,40,0.22);
            border-radius: 12px;
            font-size: 15px; color: #8c2828;
        }
        .cancelled-notice i { font-size: 20px; flex-shrink: 0; }

        /* fd-table */
        .fd-table { width: 100%; border-collapse: collapse; }
        .fd-table thead tr { border-bottom: 1.5px solid rgba(75,54,33,0.2); }
        .fd-table th {
            font-size: 10px; font-weight: 500; letter-spacing: 0.16em;
            text-transform: uppercase; color: var(--olive);
            padding: 20px 28px 16px; text-align: right;
        }
        .fd-table th:first-child { text-align: left; }
        .fd-table tbody tr { border-bottom: 1px solid rgba(75,54,33,0.1); }
        .fd-table tbody tr:last-child { border-bottom: none; }
        .fd-table tbody tr:hover { background: rgba(75,54,33,0.03); }
        .fd-table td {
            padding: 18px 28px; font-size: 15px;
            color: var(--umber); text-align: right; vertical-align: middle;
        }
        .fd-table td:first-child { text-align: left; font-weight: 500; }
        .fd-table td.muted { opacity: 0.55; }
        .fd-table tfoot tr { border-top: 2px solid rgba(75,54,33,0.22); }
        .fd-table tfoot td {
            padding: 20px 28px 24px; font-size: 17px;
            color: var(--umber); text-align: right; font-weight: 700;
        }
        .fd-table tfoot td:first-child {
            text-align: left; font-size: 11px; letter-spacing: 0.18em;
            text-transform: uppercase; color: var(--olive); font-weight: 500;
        }

        /* progress tracker */
        .progress-wrap {
            position: relative; display: flex;
            align-items: flex-start; justify-content: space-between;
        }
        .progress-track {
            position: absolute; height: 2px;
            background: rgba(75,54,33,0.18); z-index: 0;
        }
        .progress-fill {
            position: absolute; top: 0; left: 0;
            height: 100%; background: var(--olive); transition: width 0.5s ease;
        }
        .progress-step {
            position: relative; z-index: 2;
            display: flex; flex-direction: column;
            align-items: center; text-align: center; width: 20%;
        }
        .step-dot {
            border-radius: 50%; display: flex; align-items: center;
            justify-content: center; flex-shrink: 0;
            transition: all 0.3s; position: relative; z-index: 2;
        }
        .step-dot--done    { background: var(--olive); box-shadow: 0 4px 12px rgba(128,128,0,0.3); }
        .step-dot--done i  { color: #fff; }
        .step-dot--current { background: var(--umber); }
        .step-dot--current i { color: var(--champagne); }
        .step-dot--future  { background: var(--ivory); border: 2px solid rgba(75,54,33,0.2); }
        .step-dot--future i { color: rgba(75,54,33,0.3); }
        .step-dot--done .ph-fill.ph-check-circle            { display: block; }
        .step-dot--done .step-ph                             { display: none;  }
        .step-dot:not(.step-dot--done) .ph-fill.ph-check-circle { display: none;  }
        .step-dot:not(.step-dot--done) .step-ph              { display: block; }
        .step-label { letter-spacing: 0.04em; line-height: 1.35; font-weight: 400; }
        .step-label--done    { color: var(--olive); font-weight: 500; }
        .step-label--current { color: var(--umber); font-weight: 700; }
        .step-label--future  { color: rgba(75,54,33,0.38); }

        /* empty state */
        .empty-state { text-align: center; padding: 100px 24px; }
        .empty-state-icon {
            width: 80px; height: 80px; border-radius: 50%;
            background: rgba(75,54,33,0.07); border: 1.5px solid rgba(75,54,33,0.15);
            display: flex; align-items: center; justify-content: center; margin: 0 auto 28px;
        }
        .empty-state-icon i { font-size: 34px; color: rgba(75,54,33,0.3); }
        .empty-state h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 34px; font-weight: 600; color: var(--umber); margin-bottom: 10px;
        }
        .empty-state p { font-size: 16px; color: var(--umber); opacity: 0.55; margin-bottom: 32px; }

        /* delivery grid */
        .delivery-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; }
        .delivery-field label {
            display: block; font-size: 11px; font-weight: 500;
            letter-spacing: 0.18em; text-transform: uppercase;
            color: var(--olive); margin-bottom: 9px;
        }
        .delivery-field p { font-size: 15px; color: var(--umber); line-height: 1.7; }

        /* ── LANDING PAGE STYLES ── */
        .hero {
            min-height: 100vh; display: flex; align-items: center;
            padding: 120px 48px 80px; position: relative; overflow: hidden;
            background: var(--ivory);
        }
        .hero-inner { position: relative; z-index: 2; max-width: 600px; }
        .hero-tag {
            display: inline-block; font-size: 11px; letter-spacing: 0.15em;
            text-transform: uppercase; color: var(--olive);
            border: 1px solid rgba(128,128,0,0.3); padding: 6px 16px;
            border-radius: 999px; margin-bottom: 28px;
        }
        .hero-title {
            font-family: 'Cormorant Garamond', serif; font-size: 72px; font-weight: 600;
            color: var(--umber); line-height: 1.05; margin-bottom: 24px;
        }
        .hero-sub {
            font-size: 16px; font-weight: 300; color: var(--mauve);
            line-height: 1.7; max-width: 440px; margin-bottom: 40px;
        }
        .hero-actions { display: flex; gap: 20px; align-items: center; flex-wrap: wrap; }
        .hero-orb { position: absolute; border-radius: 50%; pointer-events: none; }
        .hero-orb-1 {
            width: 500px; height: 500px; right: -100px; top: 50%; transform: translateY(-50%);
            background: radial-gradient(circle, rgba(247,231,206,0.8) 0%, rgba(255,251,240,0) 70%);
        }
        .hero-orb-2 {
            width: 300px; height: 300px; right: 200px; top: 10%;
            background: radial-gradient(circle, rgba(196,164,132,0.15) 0%, transparent 70%);
        }
        .marquee-wrap { background: var(--umber); padding: 14px 0; overflow: hidden; }
        .marquee-track {
            display: flex; align-items: center; gap: 0; white-space: nowrap;
            animation: marquee 30s linear infinite;
        }
        .marquee-item {
            font-size: 12px; letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--champagne); padding: 0 24px;
        }
        .marquee-dot { color: var(--olive); font-size: 10px; }
        @keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }
        .about {
            display: grid; grid-template-columns: 1fr 1fr; gap: 80px;
            align-items: center; padding: 100px 48px; background: var(--champagne);
        }
        .about-image-wrap { position: relative; }
        .about-image-placeholder {
            background: rgba(196,164,132,0.3); border-radius: 4px 40px 4px 40px;
            height: 420px; overflow: hidden; display: flex;
            align-items: center; justify-content: center;
            border: 1px solid rgba(196,164,132,0.3);
        }
        .about-image-inner { text-align: center; }
        .placeholder-text { font-size: 12px; color: var(--mauve); margin-top: 12px; letter-spacing: 0.05em; }
        .about-badge {
            position: absolute; bottom: -20px; right: -20px; background: var(--olive);
            color: var(--ivory); border-radius: 50%; width: 100px; height: 100px;
            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 2px;
        }
        .about-badge-num { font-family: 'Cormorant Garamond', serif; font-size: 26px; font-weight: 600; line-height: 1; }
        .about-badge-label { font-size: 10px; letter-spacing: 0.05em; opacity: 0.85; text-align: center; }
        .about-body { font-size: 15px; color: var(--umber); line-height: 1.8; }
        .about-stats {
            display: flex; gap: 32px; margin-top: 40px; padding-top: 32px;
            border-top: 1px solid rgba(196,164,132,0.5);
        }
        .stat { display: flex; flex-direction: column; gap: 4px; }
        .stat-num { font-family: 'Cormorant Garamond', serif; font-size: 32px; font-weight: 600; color: var(--umber); }
        .stat-label { font-size: 12px; letter-spacing: 0.08em; color: var(--mauve); }
        .produce { padding: 100px 48px; background: var(--ivory); }
        .produce-header { margin-bottom: 48px; }
        .produce-grid { display: grid; grid-template-columns: 2fr 1fr 1fr; grid-template-rows: auto auto; gap: 16px; }
        .produce-card {
            background: var(--champagne); border-radius: 24px; padding: 36px 32px;
            border: 1px solid rgba(196,164,132,0.3);
            transition: transform 0.25s, box-shadow 0.25s;
        }
        .produce-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(75,54,33,0.08); }
        .produce-card--large { grid-row: span 2; display: flex; flex-direction: column; justify-content: flex-end; min-height: 280px; background: var(--umber); }
        .produce-card--large h3, .produce-card--large p { color: var(--champagne); }
        .produce-card--tall { background: rgba(128,128,0,0.08); border-color: rgba(128,128,0,0.2); }
        .produce-icon { font-size: 32px; margin-bottom: 16px; }
        .produce-card h3 { font-family: 'Cormorant Garamond', serif; font-size: 22px; font-weight: 600; color: var(--umber); margin-bottom: 8px; }
        .produce-card p { font-size: 14px; color: var(--mauve); line-height: 1.6; }
        .produce-card--large p { color: rgba(247,231,206,0.7); }
        .how { padding: 100px 48px; background: var(--champagne); }
        .how .section-title { text-align: center; margin-bottom: 64px; }
        .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0; position: relative; max-width: 800px; margin: 0 auto; }
        .step-line { position: absolute; top: 28px; left: 15%; right: 15%; height: 1px; background: rgba(196,164,132,0.5); z-index: 0; }
        .step { text-align: center; padding: 0 24px; position: relative; z-index: 1; }
        .step-num {
            font-family: 'Cormorant Garamond', serif; font-size: 13px; font-weight: 600;
            letter-spacing: 0.1em; color: var(--ivory); background: var(--umber);
            width: 48px; height: 48px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;
        }
        .step h3 { font-family: 'Cormorant Garamond', serif; font-size: 20px; font-weight: 600; color: var(--umber); margin-bottom: 8px; }
        .step p { font-size: 14px; color: var(--mauve); line-height: 1.6; }
        .cta { padding: 100px 48px; background: var(--umber); text-align: center; position: relative; overflow: hidden; }
        .cta-inner { position: relative; z-index: 2; }
        .cta h2 { font-family: 'Cormorant Garamond', serif; font-size: 52px; font-weight: 600; color: var(--champagne); margin-bottom: 16px; }
        .cta p { font-size: 16px; color: var(--mauve); margin-bottom: 36px; }
        .cta-orb { position: absolute; width: 600px; height: 600px; border-radius: 50%; background: radial-gradient(circle, rgba(128,128,0,0.15) 0%, transparent 70%); top: 50%; left: 50%; transform: translate(-50%, -50%); }
        .footer {
            padding: 32px 48px; background: var(--umber);
            border-top: 1px solid rgba(196,164,132,0.15);
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 16px;
        }
        .footer-logo { font-family: 'Cormorant Garamond', serif; font-size: 18px; color: var(--champagne); }
        .footer-links { display: flex; gap: 24px; }
        .footer-links a { font-size: 12px; letter-spacing: 0.08em; color: var(--mauve); text-decoration: none; transition: color 0.2s; }
        .footer-links a:hover { color: var(--champagne); }
        .footer-copy { font-size: 12px; color: var(--mauve); }

        /* ── SHARED MOBILE ── */
        @media (max-width: 768px) {
            .hero { padding: 100px 24px 60px; }
            .hero-title { font-size: 48px; }
            .about { grid-template-columns: 1fr; padding: 60px 24px; gap: 48px; }
            .produce { padding: 60px 24px; }
            .produce-grid { grid-template-columns: 1fr 1fr; }
            .produce-card--large { grid-column: span 2; grid-row: span 1; }
            .how { padding: 60px 24px; }
            .steps { grid-template-columns: 1fr; gap: 40px; }
            .step-line { display: none; }
            .cta { padding: 60px 24px; }
            .cta h2 { font-size: 36px; }
            .footer { padding: 24px; flex-direction: column; text-align: center; }
        }
        @media (max-width: 640px) {
            .page-wrap    { padding: 100px 20px 60px; }
            .page-heading { font-size: 40px; }
            .fd-card      { padding: 24px 20px; }
            .fd-table th,
            .fd-table td  { padding: 14px 16px; font-size: 13px; }
            .delivery-grid { grid-template-columns: 1fr; gap: 20px; }
        }
    </style>
</head>
<body>

<nav class="nav" id="main-nav">
    <a href="{{ route('landing') }}" class="nav-logo">
        <div class="nav-logo-leaf"></div>
        Farm Direct
    </a>

    {{-- ── DESKTOP ── --}}
    <div class="nav-right">
        @guest
            <a href="{{ route('produce') }}" class="nav-link">Produce</a>
        @endguest

        @auth
            @php $role = auth()->user()->role ?? 'customer'; @endphp
            <div class="nav-divider"></div>
            
            @if($role === 'shop')
                <span class="nav-role-pill nav-role-pill--wholesale">Wholesaler</span>
                <a href="{{ route('wholesale.index') }}" class="nav-link">Shop</a>
                <a href="{{ route('wholesale.cart') }}"  class="nav-link nav-link-cart">
                    Cart
                    @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                    @if($cartCount > 0)
                        <span class="nav-cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
                <a href="{{ route('wholesale.orders') }}" class="nav-link">Orders</a>

            @else
                <span class="nav-role-pill nav-role-pill--customer">Customer</span>
                <a href="{{ route('shop.index') }}" class="nav-link">Shop</a>
                <a href="{{ route('shop.cart') }}"  class="nav-link nav-link-cart">
                    Cart
                    @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                    @if($cartCount > 0)
                        <span class="nav-cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
                <a href="{{ route('shop.orders') }}" class="nav-link">Orders</a>
            @endif

            <div class="nav-divider"></div>
            <span class="nav-greeting">Hi, <strong>{{ auth()->user()->name }}</strong></span>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="nav-btn nav-btn--outline">Sign out</button>
            </form>

        @else
            <div class="nav-divider"></div>
            <a href="{{ route('login') }}"    class="nav-btn nav-btn--outline">Login</a>
            <a href="{{ route('register') }}" class="nav-btn nav-btn--solid">Register</a>
        @endauth
    </div>

    <button class="nav-hamburger" id="nav-toggle" aria-label="Toggle menu">
        <span></span><span></span><span></span>
    </button>
</nav>

{{-- ── MOBILE DRAWER ── --}}
<div class="nav-mobile" id="nav-mobile">
    <a href="{{ route('produce') }}" class="nav-link">Produce</a>

    @auth
        @php $role = auth()->user()->role ?? 'customer'; @endphp

        @if($role === 'shop')
            <a href="{{ route('wholesale.index') }}"  class="nav-link">Shop</a>
            <a href="{{ route('wholesale.cart') }}"   class="nav-link">Cart</a>
            <a href="{{ route('wholesale.orders') }}" class="nav-link">Orders</a>
        @else
            <a href="{{ route('shop.index') }}"  class="nav-link">Shop</a>
            <a href="{{ route('shop.cart') }}"   class="nav-link">Cart</a>
            <a href="{{ route('shop.orders') }}" class="nav-link">Orders</a>
        @endif

        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" class="nav-btn nav-btn--outline" style="width:100%">Sign out</button>
        </form>
    @else
        <a href="{{ route('login') }}"    class="nav-btn nav-btn--outline" style="text-align:center">Login</a>
        <a href="{{ route('register') }}" class="nav-btn nav-btn--solid"   style="text-align:center">Register</a>
    @endauth
</div>

<script>
    const toggle = document.getElementById('nav-toggle');
    const drawer = document.getElementById('nav-mobile');
    toggle?.addEventListener('click', () => drawer.classList.toggle('is-open'));
    drawer?.querySelectorAll('a, button').forEach(el => {
        el.addEventListener('click', () => drawer.classList.remove('is-open'));
    });
</script>

@yield('content')

<footer class="footer">
    <div class="footer-logo">Farm Direct</div>
    <div class="footer-links">
        <a href="#about">About</a>
        <a href="{{ route('produce') }}">Produce</a>
        @auth
            @if((auth()->user()->role ?? '') === 'shop')
                <a href="{{ route('wholesale.index') }}">Wholesale</a>
            @else
                <a href="{{ route('shop.index') }}">Shop</a>
            @endif
        @else
            <a href="{{ route('login') }}">Login</a>
        @endauth
    </div>
    <p class="footer-copy">© {{ date('Y') }} Farm Direct, Kerala</p>
</footer>

@stack('scripts')

</body>
</html>