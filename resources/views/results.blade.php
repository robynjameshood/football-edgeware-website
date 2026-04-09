@extends('layouts.app')

@section('title', 'Results — Football Edgeware')
@section('meta_description', 'Daily Football Edgeware result outcomes from 26/03/2026 onward, including fixture status and win/loss outcomes.')
@section('meta_keywords', 'football results, win loss, daily fixtures, football edgeware')
@section('og_image', asset('social/welcome-card.svg'))
@section('twitter_image', asset('social/welcome-card.svg'))

@section('content')
<div class="results-page">
    <div class="results-head">
        <div>
            <span class="results-kicker">Match Centre</span>
            <h1>Daily Results</h1>
        </div>
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
    .results-page{
        --results-accent:#0f766e;
        --results-accent-soft:#ccfbf1;
        --results-loss:#b91c1c;
        --results-loss-soft:#fee2e2;
        --results-ink:#0f172a;
        --results-muted:#64748b;
        max-width:1120px;
        margin:0 auto;
        padding:20px;
        border:1px solid rgba(148,163,184,0.14);
        border-radius:28px;
        background:
            radial-gradient(circle at top left, rgba(15,118,110,0.08), transparent 30%),
            linear-gradient(180deg, rgba(255,255,255,0.98), rgba(248,250,252,0.96));
        box-shadow:0 28px 80px rgba(15,23,42,0.08);
    }
    .results-head{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
    .results-kicker{
        display:inline-block;
        margin-bottom:10px;
        padding:6px 10px;
        border-radius:999px;
        background:rgba(15,118,110,0.1);
        color:var(--results-accent);
        font-size:12px;
        font-weight:700;
        letter-spacing:.14em;
        text-transform:uppercase;
    }
    .results-page h1{margin:0 0 8px;font-size:clamp(2rem, 4vw, 3rem);line-height:1}
    .results-page .lead{max-width:700px;color:var(--results-muted);margin:0 0 20px;font-size:1rem}

    .refresh-btn{
        border:1px solid rgba(15,118,110,0.18);
        background:linear-gradient(180deg,#ffffff,#ecfeff);
        color:var(--results-accent);
        border-radius:999px;
        padding:12px 18px;
        font-weight:600;
        cursor:pointer;
        box-shadow:0 12px 30px rgba(15,118,110,0.12);
        transition:transform .18s ease, box-shadow .18s ease, background .18s ease;
    }
    .refresh-btn:hover{background:#ffffff;transform:translateY(-1px);box-shadow:0 16px 30px rgba(15,118,110,0.16)}
    .refresh-btn[disabled]{opacity:.6;cursor:not-allowed}

    .dates-strip{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:24px}
    .date-pill{
        display:inline-block;
        text-decoration:none;
        color:#1f2937;
        background:rgba(255,255,255,0.82);
        border:1px solid rgba(148,163,184,0.18);
        padding:10px 14px;
        border-radius:999px;
        font-size:14px;
        backdrop-filter:blur(8px);
        box-shadow:0 10px 24px rgba(15,23,42,0.05);
        transition:all .15s ease;
    }
    .date-pill:hover{background:#f0fdfa;border-color:rgba(15,118,110,0.28);color:var(--results-accent)}
    .date-pill.active{background:linear-gradient(135deg,#0f766e,#0f172a);border-color:#0f766e;color:#ffffff;box-shadow:0 14px 30px rgba(15,118,110,0.28)}

    .notice{
        background:rgba(255,255,255,0.9);
        border:1px dashed rgba(148,163,184,0.4);
        border-radius:18px;
        padding:18px;
        color:#374151;
        margin-bottom:18px;
    }
    .notice.error{background:#fff1f2;border-color:#fda4af;color:#9f1239}
    .results-empty-state strong{display:block;margin-bottom:8px;font-size:1.1rem;color:var(--results-ink)}
    .results-empty-state p{margin:0;color:var(--results-muted)}
    .empty-state-kicker{display:inline-block;margin-bottom:10px;font-size:11px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:#0f766e}

    .results-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:18px}
    .result-card{
        position:relative;
        overflow:hidden;
        background:linear-gradient(180deg,rgba(255,255,255,0.98),rgba(248,250,252,0.95));
        border-radius:24px;
        border:1px solid rgba(226,232,240,0.95);
        padding:18px;
        box-shadow:0 18px 40px rgba(15,23,42,0.08);
    }
    .result-card::after{
        content:'';
        position:absolute;
        inset:auto -20% -45% auto;
        width:160px;
        height:160px;
        border-radius:999px;
        background:rgba(255,255,255,0.45);
        filter:blur(10px);
        pointer-events:none;
    }
    .result-card.winner{border-color:rgba(16,185,129,0.26);background:linear-gradient(180deg,rgba(240,253,250,0.96),rgba(255,255,255,0.98))}
    .result-card.loser{border-color:rgba(248,113,113,0.24);background:linear-gradient(180deg,rgba(255,241,242,0.96),rgba(255,255,255,0.98))}

    .outcome-pill{
        display:inline-flex;
        align-items:center;
        gap:12px;
        padding:10px 14px;
        border-radius:999px;
        border:1px solid rgba(148,163,184,0.16);
        background:rgba(255,255,255,0.84);
        box-shadow:0 10px 24px rgba(15,23,42,0.06);
    }
    .outcome-pill.win{background:linear-gradient(180deg,rgba(240,253,250,0.98),rgba(255,255,255,0.86));border-color:rgba(15,118,110,0.16)}
    .outcome-pill.loss{background:linear-gradient(180deg,rgba(255,241,242,0.98),rgba(255,255,255,0.86));border-color:rgba(185,28,28,0.14)}
    .outcome-dot{
        flex:0 0 auto;
        width:12px;
        height:12px;
        border-radius:999px;
        box-shadow:0 0 0 5px rgba(15,23,42,0.04);
    }
    .outcome-pill.win .outcome-dot{background:#14b8a6;box-shadow:0 0 0 5px rgba(20,184,166,0.12)}
    .outcome-pill.loss .outcome-dot{background:#ef4444;box-shadow:0 0 0 5px rgba(239,68,68,0.12)}
    .outcome-pill strong{font-size:12px;line-height:1.2;color:var(--results-ink);letter-spacing:.08em;text-transform:uppercase}

    .result-card-head{
        display:grid;
        grid-template-columns:minmax(0,1fr) auto minmax(0,1fr);
        align-items:center;
        gap:12px;
        margin-bottom:18px;
    }
    .team-stack{min-width:0}
    .team-stack.align-right{text-align:right}
    .team-label{
        display:block;
        margin-bottom:6px;
        color:var(--results-muted);
        font-size:11px;
        font-weight:700;
        letter-spacing:.12em;
        text-transform:uppercase;
    }
    .result-card-head .team{
        display:block;
        font-weight:800;
        font-size:1.05rem;
        line-height:1.25;
        color:var(--results-ink);
        word-break:break-word;
    }
    .vs-pill{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        width:44px;
        height:44px;
        border-radius:999px;
        background:#ffffff;
        color:var(--results-muted);
        font-size:12px;
        font-weight:800;
        letter-spacing:.14em;
        text-transform:uppercase;
        border:1px solid rgba(148,163,184,0.16);
        box-shadow:0 10px 24px rgba(15,23,42,0.07);
    }

    .result-stat-row{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin-bottom:12px}
    .result-stat-card{
        padding:14px;
        border-radius:18px;
        background:rgba(255,255,255,0.82);
        border:1px solid rgba(226,232,240,0.9);
        box-shadow:inset 0 1px 0 rgba(255,255,255,0.7);
    }
    .result-stat-card.accent{background:linear-gradient(180deg,rgba(240,253,250,0.95),rgba(255,255,255,0.82))}
    .stat-label{display:block;margin-bottom:6px;color:var(--results-muted);font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase}
    .stat-value{font-size:1.05rem;color:var(--results-ink)}
    .edge-meter{
        height:10px;
        border-radius:999px;
        background:rgba(148,163,184,0.18);
        overflow:hidden;
    }
    .edge-meter-bar{
        height:100%;
        border-radius:999px;
        background:linear-gradient(90deg,#14b8a6,#0f766e);
        box-shadow:0 4px 12px rgba(20,184,166,0.26);
    }
    .result-card-footer{
        margin-top:16px;
        padding-top:14px;
        border-top:1px solid rgba(148,163,184,0.14);
    }

    .result-skeleton{
        height:210px;
        border-radius:24px;
        border:1px solid rgba(226,232,240,0.95);
        background:linear-gradient(90deg,#f8fafc 0%,#e2e8f0 50%,#f8fafc 100%);
        background-size:200% 100%;
        animation:resultShimmer 1.1s linear infinite;
    }

    @keyframes resultShimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    @media (max-width:640px){
        .results-page{padding:16px;border-radius:22px}
        .dates-strip{max-height:190px;overflow:auto;padding-right:4px}
        .result-card-head{grid-template-columns:1fr;justify-items:flex-start}
        .team-stack.align-right{text-align:left}
        .vs-pill{width:auto;height:auto;padding:8px 12px}
        .result-stat-row{grid-template-columns:1fr}
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
