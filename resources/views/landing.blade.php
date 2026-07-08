@extends('layouts.public')

@section('title', 'Farm Direct — Fresh from Kerala')

@section('content')

{{-- HERO --}}
<section class="hero">
    <div class="hero-inner">
        
        {{-- Left Column: Text Content --}}
        <div class="hero-text">
            <div class="hero-tag">Est. in the heart of Kerala</div>
            <h1 class="hero-title">
                From our soil<br>
                <em>to your table</em>
            </h1>
            <p class="hero-sub">
                Fresh produce grown without compromise. Order directly from the farm — no middlemen, no cold storage, no waiting.
            </p>
            <div class="hero-actions">
                <a href="{{ route('login') }}" class="btn-primary">Order Fresh Produce</a>
                <a href="#about" class="btn-ghost">Learn about the farm</a>
            </div>
        </div>

        {{-- Right Column: Truck Image --}}
        <div class="hero-image">
            <img src="{{ asset('storage/images/truck.png') }}" alt="Farm Direct Delivery Truck">
        </div>

    </div>
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
</section>

{{-- MARQUEE STRIP --}}
<div class="marquee-wrap">
    <div class="marquee-track">
        @foreach(['Leafy Greens', 'Root Vegetables', 'Tropical Fruits', 'Fresh Herbs', 'Organic Spices', 'Seasonal Picks', 'Leafy Greens', 'Root Vegetables', 'Tropical Fruits', 'Fresh Herbs', 'Organic Spices', 'Seasonal Picks'] as $item)
            <span class="marquee-item">{{ $item }}</span>
            <span class="marquee-dot">✦</span>
        @endforeach
    </div>
</div>

{{-- ABOUT --}}
<section class="about" id="about">
    <div class="about-image-wrap">
        <div class="about-image-placeholder">
            <div class="about-image-inner">
                <svg width="64" height="64" viewBox="0 0 64 64" fill="none">
                    <path d="M32 8C18.7 8 8 18.7 8 32s10.7 24 24 24 24-10.7 24-24S45.3 8 32 8z" fill="#808000" opacity="0.15"/>
                    <path d="M32 16c-4.4 0-8 3.6-8 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8z" fill="#808000" opacity="0.3"/>
                    <path d="M20 44c0-6.6 5.4-12 12-12s12 5.4 12 12" stroke="#808000" stroke-width="2" stroke-linecap="round" opacity="0.4"/>
                </svg>
                <p class="placeholder-text">Your farm photo here</p>
            </div>
        </div>
        <div class="about-badge">
            <span class="about-badge-num">20+</span>
            <span class="about-badge-label">Years of farming</span>
        </div>
    </div>
    <div class="about-content">
        <div class="section-label">About the farm</div>
        <h2 class="section-title">Two decades of growing <em>the right way</em></h2>
        <p class="about-body">
            Nestled in the lush greenery of Kerala, our farm has been tending the land for over twenty years. We grow using traditional methods passed down through generations — chemical-free, water-conscious, and always in season.
        </p>
        <p class="about-body" style="margin-top: 16px;">
            When you order from Farm Direct, you're getting produce that was in the ground just days ago. We pack fresh, we deliver fast, and we never cut corners.
        </p>
        <div class="about-stats">
            <div class="stat">
                <span class="stat-num">100%</span>
                <span class="stat-label">Chemical free</span>
            </div>
            <div class="stat">
                <span class="stat-num">48h</span>
                <span class="stat-label">Farm to door</span>
            </div>
            <div class="stat">
                <span class="stat-num">30+</span>
                <span class="stat-label">Varieties grown</span>
            </div>
        </div>
    </div>
</section>

{{-- PRODUCE GRID --}}
<section class="produce">
    <div class="produce-header">
        <div class="section-label">What we grow</div>
        <h2 class="section-title">Seasonal, always fresh</h2>
    </div>
    <div class="produce-grid">
        <div class="produce-card produce-card--large">
            <div class="produce-icon">🥬</div>
            <h3>Leafy Greens</h3>
            <p>Spinach, amaranth, curry leaves — harvested at dawn, packed by noon</p>
        </div>
        <div class="produce-card">
            <div class="produce-icon"><i class="ph-duotone ph-carrot"></i></div>
            <h3>Root Vegetables</h3>
            <p>Tapioca, yam, sweet potato — straight from Kerala soil</p>
        </div>
            <div class="produce-card">
            <div class="produce-icon"><i class="ph-duotone ph-jar-label"></i></div>
            <h3>Farm Condiments</h3>
            <p>Honey, Pickles and other condiments — straight from Kerala soil</p>
        </div>
        <div class="produce-card">
            <div class="produce-icon"><i class="ph-duotone ph-cherries"></i></div>
            <h3>Tropical Fruits</h3>
            <p>Banana, papaya, jackfruit — naturally ripened</p>
        </div>
        <div class="produce-card produce-card--tall">
            <div class="produce-icon"><i class="ph-duotone ph-clover"></i></div>
            <h3>Herbs & Spices</h3>
            <p>Ginger, turmeric, lemongrass — the backbone of Kerala cooking</p>
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="how">
    <div class="section-label" style="text-align:center">How it works</div>
    <h2 class="section-title" style="text-align:center">Fresh produce in three steps</h2>
    <div class="steps">
        <div class="step-line"></div>
        <div class="step">
            <div class="step-num">01</div>
            <h3>Browse the shop</h3>
            <p>See what's in season, filter by category, add to cart</p>
        </div>
        <div class="step">
            <div class="step-num">02</div>
            <h3>Place your order</h3>
            <p>Checkout with your delivery address and any notes</p>
        </div>
        <div class="step">
            <div class="step-num">03</div>
            <h3>Packed & delivered</h3>
            <p>We pick, pack, and deliver fresh — track every step</p>
        </div>
    </div>
</section>

{{-- FEEDBACK STRIP --}}
@php $landingFeedbacks = \App\Models\Feedback::with('user')->latest()->take(3)->get(); @endphp
@if($landingFeedbacks->isNotEmpty())
<section style="padding:100px 48px; background:var(--champagne);">
    <div style="max-width:900px; margin:0 auto;">
        <div class="section-label">What our customers say</div>
        <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:48px; flex-wrap:wrap; gap:16px;">
            <h2 class="section-title" style="margin-bottom:0;">Real stories, <em>real farms</em></h2>
            <a href="{{ route('feedback') }}" class="btn-ghost">All reviews &rarr;</a>
        </div>
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px;">
            @foreach($landingFeedbacks as $fb)
            <div style="background:var(--ivory); border:1.5px solid rgba(75,54,33,0.12); border-radius:20px; padding:28px 28px 24px;">
                <div style="display:flex; gap:3px; margin-bottom:14px;">
                    @for($s = 1; $s <= 5; $s++)
                        <i class="ph-fill ph-star" style="font-size:14px; color:{{ $s <= $fb->rating ? '#808000' : 'rgba(75,54,33,0.15)' }};"></i>
                    @endfor
                </div>
                <p style="font-size:14px; color:var(--umber); line-height:1.7; margin-bottom:20px; opacity:0.85;">"{{ $fb->comment }}"</p>
                <div style="display:flex; align-items:center; gap:10px; border-top:1px solid rgba(75,54,33,0.1); padding-top:16px;">
                    <div style="width:32px; height:32px; border-radius:50%; background:var(--champagne); border:1.5px solid rgba(75,54,33,0.18); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:600; color:var(--umber);">
                        {{ strtoupper(substr($fb->user->name, 0, 2)) }}
                    </div>
                    <div>
                        <p style="font-size:13px; font-weight:600; color:var(--umber); line-height:1;">{{ $fb->user->name }}</p>
                        <p style="font-size:11px; color:var(--mauve); margin-top:2px;">{{ $fb->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="cta">
    <div class="cta-inner">
        <h2>Ready to taste the difference?</h2>
        <p>Join our customers ordering farm-fresh produce every week.</p>
        <a href="{{ route('login') }}" class="btn-primary btn-primary--dark">Get started</a>
    </div>
    <div class="cta-orb"></div>
</section>

@endsection