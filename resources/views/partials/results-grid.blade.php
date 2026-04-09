@if ($fetchError)
    <div class="notice error">{{ $fetchError }}</div>
@endif

@if (empty($records))
    <div class="notice">No results were returned for {{ \Illuminate\Support\Carbon::parse($selectedDate)->format('d/m/Y') }}.</div>
@else
    <div class="results-grid">
        @foreach ($records as $result)
            <article class="result-card {{ $result['isWinner'] ? 'winner' : 'loser' }}">
                <header class="result-card-head">
                    <span class="team">{{ $result['home'] }}</span>
                    <span class="vs">vs</span>
                    <span class="team">{{ $result['away'] }}</span>
                </header>

                <div class="result-card-body">
                    <p><strong>Current Half:</strong> {{ $result['currentHalf'] }}</p>
                    <p>
                        <strong>Outcome:</strong>
                        <span class="outcome {{ $result['isWinner'] ? 'win' : 'loss' }}">
                            {{ $result['isWinner'] ? 'Win' : 'Loss' }}
                        </span>
                    </p>
                    @if (! is_null($result['percentage']))
                        <p><strong>Edge %:</strong> {{ $result['percentage'] }}%</p>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
@endif
