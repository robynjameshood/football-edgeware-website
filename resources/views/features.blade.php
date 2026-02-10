@extends('layouts.app')

@section('title', 'Football Prediction Features: Goals, BTTS, Analytics | Football Edgeware')
@section('meta_description', 'Explore Football Edgeware features: first-half and second-half goal analytics, BTTS insights, and Roller tools to make smarter football predictions.')
@section('meta_keywords', 'football, features, first half goals, second half goals, both teams to score, roller, predictions, Football Edgeware')
@section('og_image', asset('social/welcome-card.svg'))
@section('twitter_image', asset('social/welcome-card.svg'))

@section('content')
<div class="features">
    <h1>Features</h1>
    <p class="lead">Quick access to match prediction features and tools.</p>

    <div class="cards">
        <div class="card">
            <h3>First Half Goals</h3>
            <p>Track and predict goals scored in the first half.</p>
        </div>

        <div class="card">
            <h3>Second Half Goals</h3>
            <p>Track and predict goals scored in the second half.</p>
        </div>

        <div class="card">
            <h3>Both Teams To Score</h3>
            <p>Analyze probability that both teams will score.</p>
        </div>

        <div class="card">
            <h3>Roller</h3>
            <p>Our one-of-a-kind roller system.</p>
        </div>

        <div class="card">
            <h3>Notifications</h3>
            <p>Our state of the art system provides notifications 24/7.</p>
        </div>
    </div>
</div>

<style>
    .features h1{margin:0 0 8px}
    .features .lead{color:#374151;margin-bottom:16px}
    .cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px}
    .card{background:#fff;border-radius:10px;padding:18px;box-shadow:0 6px 18px rgba(2,6,23,0.08);border:1px solid rgba(2,6,23,0.03)}
    .card h3{margin-top:0}

    /* Mobile adjustments: center header/lead and make cards full-width with comfortable padding */
    @media (max-width:640px) {
        .features h1, .features .lead { text-align: center; }
        .cards { grid-template-columns: 1fr; gap:14px; }
        .card { padding:16px; margin:0 auto; max-width:96%; }
    }
</style>
@endsection

