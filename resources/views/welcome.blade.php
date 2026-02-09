@extends('layouts.app')

@section('title', 'Welcome — Football Edgeware')
@section('meta_description', 'Football Edgeware — download the app for live match stats, predictions and analytics. Available on the App Store and Google Play.')
@section('meta_keywords', 'football, predictions, live stats, app, Football Edgeware')

{{-- Prefer a raster PNG for social previews (add public/social/welcome-card.png) --}}
@section('og_image', asset('social/welcome-card.png'))
@section('twitter_image', asset('social/welcome-card.png'))

@section('content')
<div class="home-hero">
    <h1>Welcome to {{ config('app.name') }}</h1>
    <p class="lead">Get the mobile app for quick access to features and live updates.</p>

    {{-- Carousel: loads images from public/images/carousel/ --}}
    @php
        $carouselPath = public_path('images/carousel');
        $images = [];
        if (file_exists($carouselPath)) {
            foreach (glob($carouselPath.'/*.{jpg,jpeg,png,gif,webp,svg}', GLOB_BRACE) as $f) {
                $images[] = asset('images/carousel/'.basename($f));
            }
        }
        // Fallback to the welcome social image if no images provided
        if (empty($images)) {
            // prefer PNG for social image; keep SVG as a fallback if PNG not present
            $images = [asset('social/welcome-card.png'), asset('social/welcome-card.svg')];
        }
    @endphp

    <div class="carousel" id="homeCarousel" tabindex="0" aria-roledescription="carousel">
        <div class="carousel-track">
            @foreach($images as $idx => $img)
                <div class="carousel-slide" data-index="{{ $idx }}" aria-hidden="{{ $idx === 0 ? 'false' : 'true' }}">
                    <img src="{{ $img }}" alt="{{ config('app.name') }} image {{ $idx + 1 }}" loading="lazy">
                </div>
            @endforeach
        </div>

        <button class="carousel-btn prev" aria-label="Previous slide">‹</button>
        <button class="carousel-btn next" aria-label="Next slide">›</button>

        <div class="carousel-indicators" role="tablist">
            @foreach($images as $idx => $img)
                <button class="indicator {{ $idx === 0 ? 'active' : '' }}" data-slide="{{ $idx }}" aria-label="Go to slide {{ $idx + 1 }}" role="tab" aria-selected="{{ $idx === 0 ? 'true' : 'false' }}"></button>
            @endforeach
        </div>
    </div>

    <div style="height:18px"></div>

    <div class="store-cards">
        <a class="store-card ios" href="https://apps.apple.com/us/app/football-edgeware/id6748034545" target="_blank" rel="noopener noreferrer">
            <div class="store-card-inner">
                <div class="store-logo"></div>
                <div class="store-info">
                    <div class="store-sub">Download on the</div>
                    <div class="store-title">App Store</div>
                </div>
            </div>
        </a>

        <a class="store-card android" href="https://play.google.com/store/search?q=football%20edgeware&c=apps&hl=en_GB" target="_blank" rel="noopener noreferrer">
            <div class="store-card-inner">
                <div class="store-logo">▶</div>
                <div class="store-info">
                    <div class="store-sub">Get it on</div>
                    <div class="store-title">Google Play</div>
                </div>
            </div>
        </a>
    </div>
</div>

<style>
    .home-hero{ text-align:center; margin:0 auto 16px; max-width:980px }
    .home-hero h1{margin:0 0 6px}
    .home-hero .lead{color:#6b7280;margin-bottom:18px}

    /* Carousel styles */
    .carousel{position:relative;overflow:hidden;border-radius:12px;margin-bottom:18px}
    .carousel-track{display:flex;transition:transform .45s cubic-bezier(.22,.9,.32,1)}
    .carousel-slide{min-width:100%;flex-shrink:0;display:flex;align-items:center;justify-content:center}
    /* Ensure images are fully visible (contain) and centered with light letterboxing */
    .carousel-slide{background:#f3f4f6}
    .carousel-slide img{max-width:100%;max-height:320px;object-fit:contain;display:block}

    .carousel-btn{position:absolute;top:50%;transform:translateY(-50%);z-index:10;background:rgba(0,0,0,0.45);color:#fff;border:0;padding:10px;border-radius:8px;cursor:pointer}
    .carousel-btn.prev{left:12px}
    .carousel-btn.next{right:12px}
    .carousel-btn:focus{outline:2px solid rgba(255,255,255,0.7)}

    .carousel-indicators{position:absolute;left:50%;transform:translateX(-50%);bottom:12px;display:flex;gap:8px;z-index:10}
    .carousel-indicators .indicator{width:10px;height:10px;border-radius:50%;background:rgba(255,255,255,0.6);border:0;cursor:pointer}
    .carousel-indicators .indicator.active{background:#fff;box-shadow:0 0 0 3px rgba(0,0,0,0.06)}

    /* Store cards */
    .store-cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;align-items:stretch}
    .store-card{background:#fff;border-radius:12px;padding:18px;text-decoration:none;color:inherit;box-shadow:0 8px 24px rgba(2,6,23,0.08);transition:transform .18s ease,box-shadow .18s;display:flex;align-items:center;justify-content:center}
    .store-card:hover{transform:translateY(-6px);box-shadow:0 18px 40px rgba(2,6,23,0.12)}

    .store-card.ios{background:linear-gradient(135deg,#0b69ff,#2fb5ff);color:#fff}
    .store-card.android{background:linear-gradient(135deg,#34a853,#0f9d58);color:#fff}

    .store-card-inner{display:flex;align-items:center;gap:16px}
    .store-logo{font-size:34px;opacity:0.95}
    .store-sub{font-size:12px;opacity:0.95}
    .store-title{font-size:18px;font-weight:700}

    @media (max-width:480px){
        .store-card{padding:14px}
        .store-logo{font-size:28px}
        .store-title{font-size:16px}
        .carousel-slide img{height:200px}
    }
</style>

<script>
    (function() {
        const carousel = document.getElementById('homeCarousel');
        if (!carousel) return;

        const track = carousel.querySelector('.carousel-track');
        const slides = Array.from(carousel.querySelectorAll('.carousel-slide'));
        const prevBtn = carousel.querySelector('.carousel-btn.prev');
        const nextBtn = carousel.querySelector('.carousel-btn.next');
        const indicators = Array.from(carousel.querySelectorAll('.indicator'));
        let current = 0;
        const total = slides.length;
        const intervalMs = 4500;
        let timer = null;

        function goTo(index) {
            current = (index + total) % total;
            track.style.transform = 'translateX(' + (-current * 100) + '%)';
            slides.forEach((s, i) => s.setAttribute('aria-hidden', i === current ? 'false' : 'true'));
            indicators.forEach((b, i) => { b.classList.toggle('active', i === current); b.setAttribute('aria-selected', i === current ? 'true' : 'false'); });
        }

        function next() { goTo(current + 1); }
        function prev() { goTo(current - 1); }

        function start() { if (timer) clearInterval(timer); timer = setInterval(next, intervalMs); }
        function stop() { if (timer) { clearInterval(timer); timer = null; } }

        nextBtn.addEventListener('click', () => { next(); start(); });
        prevBtn.addEventListener('click', () => { prev(); start(); });

        indicators.forEach((btn) => {
            btn.addEventListener('click', () => { goTo(parseInt(btn.dataset.slide)); start(); });
        });

        carousel.addEventListener('focusin', stop);
        carousel.addEventListener('focusout', start);
        carousel.addEventListener('mouseenter', stop);
        carousel.addEventListener('mouseleave', start);

        // keyboard
        carousel.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') { prev(); start(); }
            if (e.key === 'ArrowRight') { next(); start(); }
        });

        // init
        goTo(0);
        start();
    })();
</script>
@endsection
