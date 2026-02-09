@extends('layouts.app')

@section('title', 'Pricing — Football Edgeware')
@section('meta_description', 'Affordable plans for Football Edgeware. Weekly and Monthly subscriptions with easy cancel.')
@section('meta_keywords', 'pricing, subscription, weekly, monthly, football, Football Edgeware, betting, predictions')
@section('og_image', asset('social/welcome-card.svg'))
@section('twitter_image', asset('social/welcome-card.svg'))

@section('content')
<div class="pricing-page">
    <h1>Pricing</h1>
    <p class="lead">Choose a plan that works for you — simple, transparent pricing.</p>

    <div class="pricing-cards">
        <div class="pricing-card">
            <div class="price-badge">£4.99</div>
            <h3>Weekly</h3>
            <p>Perfect for short-term access and quick analysis.</p>
            <ul>
                <li>All features included</li>
                <li>Auto-renewal</li>
                <li>Cancel anytime</li>
            </ul>
        </div>

        <div class="pricing-card featured">
            <div class="price-badge">£14.99</div>
            <h3>Monthly</h3>
            <p>Best value for regular users who want continuous access.</p>
            <ul>
                <li>All features included</li>
                <li>Auto-renewal</li>
                <li>Save vs weekly</li>
                <li>Cancel anytime</li>
            </ul>
        </div>
    </div>

    <div class="download-cards">
        <div class="download-card">
            <h3>Download on iOS</h3>
            <p class="small">Get the Football Edgeware app from the App Store.</p>
            <a class="store-card ios" href="https://apps.apple.com/us/app/football-edgeware/id6748034545" target="_blank" rel="noopener noreferrer">
                <div class="store-card-inner">
                    <div class="store-logo"></div>
                    <div class="store-info">
                        <div class="store-sub">Download on the</div>
                        <div class="store-title">App Store</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="download-card">
            <h3>Download on Android</h3>
            <p class="small">Find Football Edgeware on Google Play.</p>
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

    <p class="small">Prices are shown in GBP. Taxes may apply.</p>
</div>

<style>
    .pricing-page{max-width:1000px;margin:0 auto;text-align:center}
    .pricing-page h1{margin-bottom:6px}
    .pricing-page .lead{color:#374151;margin-bottom:18px}

    .pricing-cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px;align-items:stretch}
    .pricing-card{background:#fff;border-radius:12px;padding:20px;box-shadow:0 12px 30px rgba(2,6,23,0.06);border:1px solid rgba(2,6,23,0.03);display:flex;flex-direction:column;align-items:center}
    .pricing-card.featured{border-color:rgba(13,110,253,0.14);box-shadow:0 18px 36px rgba(2,6,23,0.08);transform:translateY(-6px)}
    .price-badge{font-weight:800;font-size:28px;color:#0d6efd;margin-bottom:6px}
    .pricing-card h3{margin:6px 0}
    .pricing-card ul{list-style:none;padding:0;margin:10px 0 18px}
    .pricing-card li{margin:6px 0;color:#374151}
    .btn{display:inline-block;background:#0d6efd;color:#fff;padding:10px 18px;border-radius:8px;text-decoration:none}
    .small{color:#6b7280;font-size:13px;margin-top:14px}

    /* Download cards (new section) */
    .download-cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px;margin-top:22px;align-items:start}
    .download-card{background:transparent;border-radius:12px;padding:12px;text-align:center}
    .download-card h3{margin:6px 0}
    .download-card .small{margin-bottom:10px}

    /* Store card styles (match home page) */
    .card-store{display:flex;gap:14px;justify-content:center;margin-top:14px;flex-wrap:wrap}
    .store-card{background:#fff;border-radius:12px;padding:14px;text-decoration:none;color:inherit;box-shadow:0 10px 30px rgba(2,6,23,0.08);border:1px solid rgba(2,6,23,0.03);display:flex;align-items:center;justify-content:center;min-width:170px}
    .store-card:hover{transform:translateY(-4px);box-shadow:0 20px 44px rgba(2,6,23,0.12)}
    .store-card.ios{background:linear-gradient(135deg,#0b69ff,#2fb5ff);color:#fff}
    .store-card.android{background:linear-gradient(135deg,#34a853,#0f9d58);color:#fff}
    .store-card-inner{display:flex;align-items:center;gap:12px}
    .store-logo{font-size:22px;opacity:0.95}
    .store-sub{font-size:12px;opacity:0.92}
    .store-title{font-size:15px;font-weight:700}
    .store-card, .store-card a{ text-decoration:none }
    @media (max-width:480px){
        .card-store{flex-direction:column;align-items:center}
        .store-card{width:min(360px,95%);padding:12px}
        .download-cards{grid-template-columns:1fr}
    }
</style>
@endsection

