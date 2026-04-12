<nav>
    <div class="nav-links">
        <a href="{{ url('/') }}">Home</a>
        <a href="{{ route('features') }}">Features</a>
        <a href="{{ url('/pricing') }}">Pricing</a>
        <a href="{{ route('results') }}">Results</a>
        <a href="#">About</a>
            @if(session('is_admin'))
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" style="background:none;border:none;color:inherit;cursor:pointer;font:inherit;padding:0 8px;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
            @endif
    </div>
</nav>
