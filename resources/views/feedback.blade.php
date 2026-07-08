@extends('layouts.public')

@section('title', 'Customer Reviews — Farm Direct')

@section('content')
<div class="page-wrap">

    <h1 class="page-heading">Customer Reviews</h1>
    <p class="page-sub">Honest feedback from our farm community</p>

    @include('partials.flash')

    {{-- Submit form (auth only) --}}
    @auth
    <div class="fd-card" style="margin-bottom:2.5rem;">
        <div class="fd-card-label"><i class="ph ph-pencil-simple"></i> Leave a Review</div>
        <form method="POST" action="{{ route('feedback.store') }}">
            @csrf

            {{-- Name display --}}
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
                <div style="width:36px; height:36px; border-radius:50%; background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:600; color:var(--umber);">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <p style="font-size:14px; font-weight:600; color:var(--umber); line-height:1;">{{ auth()->user()->name }}</p>
                    <p style="font-size:11px; color:var(--mauve);">Posting as yourself</p>
                </div>
            </div>

            {{-- Star rating --}}
            <div style="margin-bottom:20px;">
                <label style="font-size:11px; font-weight:500; letter-spacing:0.12em; text-transform:uppercase; color:var(--olive); display:block; margin-bottom:10px;">Rating</label>
                <div id="star-picker" style="display:flex; gap:6px;">
                    @for($s = 1; $s <= 5; $s++)
                    <label style="cursor:pointer;">
                        <input type="radio" name="rating" value="{{ $s }}" style="display:none;" class="star-input" {{ old('rating') == $s ? 'checked' : '' }}>
                        <i class="ph-fill ph-star star-icon" data-val="{{ $s }}" style="font-size:28px; color:rgba(75,54,33,0.15); transition:color 0.15s; cursor:pointer;"></i>
                    </label>
                    @endfor
                </div>
                @error('rating') <p style="font-size:12px; color:#8c2828; margin-top:4px;">{{ $message }}</p> @enderror
            </div>

            {{-- Comment --}}
            <div style="margin-bottom:24px;">
                <label style="font-size:11px; font-weight:500; letter-spacing:0.12em; text-transform:uppercase; color:var(--olive); display:block; margin-bottom:8px;">Your Review</label>
                <textarea name="comment" rows="4" placeholder="Tell us about your experience…"
                    style="width:100%; padding:0.7rem 1rem; font-family:'Jost',sans-serif; font-size:14px; color:var(--umber); background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); border-radius:10px; outline:none; resize:none;">{{ old('comment') }}</textarea>
                @error('comment') <p style="font-size:12px; color:#8c2828; margin-top:4px;">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn-primary" style="padding:12px 32px; font-size:12px;">Submit Review</button>
        </form>
    </div>
    @else
    <div style="padding:20px 24px; background:var(--champagne); border:1.5px solid rgba(75,54,33,0.15); border-radius:14px; margin-bottom:2rem; font-size:14px; color:var(--umber);">
        <a href="{{ route('login') }}" class="fd-link">Sign in</a> to leave a review.
    </div>
    @endauth

    {{-- All reviews --}}
    @forelse($feedbacks as $fb)
    <div style="padding:24px 28px; background:var(--champagne); border:1.5px solid rgba(75,54,33,0.12); border-radius:16px; margin-bottom:14px;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px; flex-wrap:wrap; gap:10px;">
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:36px; height:36px; border-radius:50%; background:var(--ivory); border:1.5px solid rgba(75,54,33,0.18); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:600; color:var(--umber);">
                    {{ strtoupper(substr($fb->user->name, 0, 2)) }}
                </div>
                <div>
                    <p style="font-size:14px; font-weight:600; color:var(--umber); line-height:1;">{{ $fb->user->name }}</p>
                    <p style="font-size:11px; color:var(--mauve); margin-top:2px;">{{ $fb->created_at->format('d M Y') }}</p>
                </div>
            </div>
            <div style="display:flex; gap:3px;">
                @for($s = 1; $s <= 5; $s++)
                    <i class="ph-fill ph-star" style="font-size:14px; color:{{ $s <= $fb->rating ? '#808000' : 'rgba(75,54,33,0.15)' }};"></i>
                @endfor
            </div>
        </div>
        <p style="font-size:15px; color:var(--umber); line-height:1.7; opacity:0.85;">{{ $fb->comment }}</p>
    </div>
    @empty
    <div class="empty-state">
        <div class="empty-state-icon"><i class="ph ph-chat-dots"></i></div>
        <h3>No reviews yet</h3>
        <p>Be the first to share your experience.</p>
    </div>
    @endforelse

</div>

@push('scripts')
<script>
    const stars  = document.querySelectorAll('.star-icon');
    const inputs = document.querySelectorAll('.star-input');

    function paintStars(upTo) {
        stars.forEach(s => {
            s.style.color = parseInt(s.dataset.val) <= upTo ? '#808000' : 'rgba(75,54,33,0.15)';
        });
    }

    // Init from old value
    const checked = document.querySelector('.star-input:checked');
    if (checked) paintStars(parseInt(checked.value));

    stars.forEach(star => {
        star.addEventListener('mouseover', () => paintStars(parseInt(star.dataset.val)));
        star.addEventListener('mouseleave', () => {
            const sel = document.querySelector('.star-input:checked');
            paintStars(sel ? parseInt(sel.value) : 0);
        });
        star.addEventListener('click', () => {
            const val = parseInt(star.dataset.val);
            inputs[val - 1].checked = true;
            paintStars(val);
        });
    });
</script>
@endpush

@endsection