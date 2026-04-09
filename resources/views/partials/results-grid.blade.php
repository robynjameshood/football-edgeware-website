@if ($fetchError)
    <div class="notice error">{{ $fetchError }}</div>
@endif

@if (empty($records))
    <div class="notice results-empty-state">
        <span class="empty-state-kicker">No fixtures returned</span>
        <strong>{{ \Illuminate\Support\Carbon::parse($selectedDate)->format('d/m/Y') }}</strong>
        <p>There are no result cards available for this date yet. Try another day or refresh again shortly.</p>
    </div>
@else
    <div class="results-grid">
        @foreach ($records as $result)
            <article class="result-card {{ $result['isWinner'] ? 'winner' : 'loser' }}">
                <div class="result-card-head">
                    <div class="team-stack">
                        <span class="team-label">Home</span>
                        <span class="team">{{ $result['home'] }}</span>
                    </div>
                    <span class="vs-pill">vs</span>
                    <div class="team-stack align-right">
                        <span class="team-label">Away</span>
                        <span class="team">{{ $result['away'] }}</span>
                    </div>
                </div>

                <div class="result-card-body">
                    <div class="result-stat-row">
                        <div class="result-stat-card">
                            <span class="stat-label">Current Half</span>
                            <strong class="stat-value">{{ $result['currentHalf'] }}</strong>
                        </div>
                        <div class="result-stat-card accent">
                            <span class="stat-label">Match Edge</span>
                            <strong class="stat-value">{{ is_null($result['percentage']) ? 'N/A' : $result['percentage'].'%' }}</strong>
                        </div>
                    </div>

                    @if (! is_null($result['percentage']))
                        <div class="edge-meter" aria-label="Edge percentage {{ $result['percentage'] }} percent">
                            <div class="edge-meter-bar" style="width: {{ max(0, min(100, (float) $result['percentage'])) }}%;"></div>
                        </div>
                    @endif
                </div>

                <footer class="result-card-footer">
                    <div class="outcome-pill {{ $result['isWinner'] ? 'win' : 'loss' }}">
                        <span class="outcome-dot" aria-hidden="true"></span>
                        <strong>{{ $result['isWinner'] ? 'Edge landed' : 'Edge missed' }}</strong>
                    </div>
                </footer>
            </article>
        @endforeach
    </div>
@endif
