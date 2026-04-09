@extends('layouts.app')

@section('title', 'Results — Football Edgeware')
@section('meta_description', 'Daily Football Edgeware result outcomes from 26/03/2026 onward, including fixture status and win/loss outcomes.')
@section('meta_keywords', 'football results, win loss, daily fixtures, football edgeware')
@section('og_image', asset('social/welcome-card.svg'))
@section('twitter_image', asset('social/welcome-card.svg'))

@section('content')
<div class="results-page">
    <div class="results-head">
        <h1>Daily Results</h1>
        <button id="refreshResults" class="refresh-btn" type="button">Refresh</button>
    </div>
    <p class="lead">Select a date from 26/03/2026 onward to view win/loss outcomes for that day.</p>

    <div class="dates-strip" aria-label="Available result dates">
        @foreach ($availableDates as $date)
            <a
                class="date-pill {{ $selectedDate === $date['value'] ? 'active' : '' }}"
                href="{{ route('results', ['date' => $date['value']]) }}"
            >
                {{ $date['label'] }}
            </a>
        @endforeach
    </div>

    <div id="resultsContainer">
        @include('partials.results-grid', [
            'records' => $records,
            'selectedDate' => $selectedDate,
            'fetchError' => $fetchError,
        ])
    </div>
</div>

<style>
    .results-page{max-width:1100px;margin:0 auto}
    .results-head{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
    .results-page h1{margin:0 0 8px}
    .results-page .lead{color:#4b5563;margin:0 0 16px}

    .refresh-btn{
        border:1px solid #bfdbfe;
        background:#eff6ff;
        color:#1d4ed8;
        border-radius:10px;
        padding:8px 12px;
        font-weight:600;
        cursor:pointer;
        transition:all .15s ease;
    }
    .refresh-btn:hover{background:#dbeafe}
    .refresh-btn[disabled]{opacity:.6;cursor:not-allowed}

    .dates-strip{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:18px}
    .date-pill{
        display:inline-block;
        text-decoration:none;
        color:#1f2937;
        background:#e5e7eb;
        border:1px solid #d1d5db;
        padding:8px 12px;
        border-radius:999px;
        font-size:14px;
        transition:all .15s ease;
    }
    .date-pill:hover{background:#dbeafe;border-color:#93c5fd;color:#1d4ed8}
    .date-pill.active{background:#1d4ed8;border-color:#1d4ed8;color:#ffffff;box-shadow:0 10px 22px rgba(29,78,216,0.25)}

    .notice{
        background:#f9fafb;
        border:1px dashed #d1d5db;
        border-radius:10px;
        padding:14px;
        color:#374151;
        margin-bottom:14px;
    }
    .notice.error{background:#fef2f2;border-color:#fecaca;color:#991b1b}

    .results-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:14px}
    .result-card{
        background:#ffffff;
        border-radius:12px;
        border:1px solid #e5e7eb;
        padding:14px;
        box-shadow:0 8px 20px rgba(2,6,23,0.06);
    }
    .result-card.winner{border-color:#86efac}
    .result-card.loser{border-color:#fca5a5}

    .result-card-head{display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:8px}
    .result-card-head .team{font-weight:700;font-size:14px;line-height:1.2}
    .result-card-head .vs{font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:.06em}

    .result-card-body p{margin:8px 0;color:#374151;font-size:14px}
    .outcome{display:inline-block;padding:2px 10px;border-radius:999px;font-weight:700;font-size:12px}
    .outcome.win{background:#dcfce7;color:#166534}
    .outcome.loss{background:#fee2e2;color:#991b1b}

    .result-skeleton{
        height:126px;
        border-radius:12px;
        border:1px solid #e5e7eb;
        background:linear-gradient(90deg,#f3f4f6 0%,#e5e7eb 50%,#f3f4f6 100%);
        background-size:200% 100%;
        animation:resultShimmer 1.1s linear infinite;
    }

    @keyframes resultShimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    @media (max-width:640px){
        .dates-strip{max-height:190px;overflow:auto;padding-right:4px}
        .result-card-head{flex-direction:column;align-items:flex-start}
    }
</style>

<script>
    (function () {
        const refreshBtn = document.getElementById('refreshResults');
        const container = document.getElementById('resultsContainer');

        if (!refreshBtn || !container) {
            return;
        }

        function renderSkeleton(count) {
            const cards = [];
            for (let i = 0; i < count; i += 1) {
                cards.push('<div class="result-skeleton" aria-hidden="true"></div>');
            }
            container.innerHTML = '<div class="results-grid">' + cards.join('') + '</div>';
        }

        async function refreshResults() {
            refreshBtn.disabled = true;
            const originalLabel = refreshBtn.textContent;
            refreshBtn.textContent = 'Refreshing...';
            renderSkeleton(6);

            try {
                const response = await fetch(window.location.href, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to refresh results');
                }

                const payload = await response.json();
                container.innerHTML = payload.html || '<div class="notice">No data returned.</div>';
            } catch (error) {
                container.innerHTML = '<div class="notice error">Unable to refresh results right now. Please try again.</div>';
            } finally {
                refreshBtn.disabled = false;
                refreshBtn.textContent = originalLabel;
            }
        }

        refreshBtn.addEventListener('click', refreshResults);
    })();
</script>
@endsection
