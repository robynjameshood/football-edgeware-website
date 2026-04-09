<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Page title: default to app name --}}
    <title>@yield('title', config('app.name', 'Football Edgeware'))</title>

    {{-- Basic SEO meta tags with overridable Blade sections --}}
    <meta name="description" content="@yield('meta_description', 'Football Edgeware — football predictions, live stats and match analytics.')">
    <meta name="keywords" content="@yield('meta_keywords', 'football, predictions, stats, betting, edgeware')">
    <meta name="robots" content="@yield('meta_robots', 'index,follow')">
    <link rel="canonical" href="@yield('canonical', request()->fullUrl())" />

    {{-- Open Graph / Facebook --}}
    <meta property="og:title" content="@yield('og_title', View::yieldContent('title', config('app.name', 'Football Edgeware')))" />
    <meta property="og:description" content="@yield('og_description', View::yieldContent('meta_description', 'Football Edgeware — football predictions, live stats and match analytics.'))" />
    <meta property="og:type" content="@yield('og_type', 'website')" />
    <meta property="og:url" content="@yield('og_url', request()->fullUrl())" />
    <meta property="og:image" content="@yield('og_image', asset('logo.png'))" />

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:title" content="@yield('twitter_title', View::yieldContent('title', config('app.name', 'Football Edgeware')))" />
    <meta name="twitter:description" content="@yield('twitter_description', View::yieldContent('meta_description', 'Football Edgeware — football predictions, live stats and match analytics.'))" />
    <meta name="twitter:image" content="@yield('twitter_image', asset('logo.png'))" />

    <style>
        /* Simple, responsive layout + horizontal navbar styles */
        :root{ --nav-height:56px; --accent:#0d6efd; }
        /* global reset to avoid layout rounding issues on mobile */
        *,*::before,*::after{box-sizing:border-box}
        html,body{width:100%;height:100%;margin:0;font-family:Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; background:#f8fafc;color:#111827;overflow-x:hidden}
        .site-wrapper{min-height:100%;display:flex;flex-direction:column}
        /* Full-bleed header bar (use width:100% + safe-area insets) */
        header{
            background:var(--accent);color:#fff;box-shadow:0 1px 0 rgba(0,0,0,0.04);
            position:relative;width:100%;left:0;right:0;margin:0;min-width:100%;
            /* ensure content doesn't hit the notch on iOS */
            padding-left:env(safe-area-inset-left);padding-right:env(safe-area-inset-right);
            -webkit-font-smoothing:antialiased;
        }
        header .container{}
        .container{width:100%;max-width:1100px;margin:0 auto;padding:0 16px}
        /* Navbar */
        .navbar{height:var(--nav-height);display:flex;align-items:center;justify-content:space-between}
        .brand{font-weight:700;color:#ffffff;text-decoration:none;font-size:18px;margin-right:16px}
        /* hide mobile nav toggle by default (show it only on small screens) */
        .nav-toggle{display:none;position:absolute;right:12px;top:50%;transform:translateY(-50%);z-index:60;padding:6px 8px}
        /* Let the nav-links take up remaining horizontal space so links can spread */
        .nav-links{display:flex;gap:12px;align-items:center;flex:1;justify-content:center}
        .nav-links a{display:inline-block;padding:8px 12px;color:rgba(255,255,255,0.95);text-decoration:none;border-radius:6px}
        .nav-links a:hover{background:rgba(255,255,255,0.12);color:#fff}
        .main{flex:1;padding:28px 0}
        footer{padding:20px 0;text-align:center;color:#6b7280;font-size:14px}
        /* Mobile/tablet breakpoints */
        @media (max-width:1024px) {
            .container{max-width:920px;padding:0 14px}
            .brand{font-size:17px}
            .main{padding:22px 0}
        }

        @media (max-width:640px){
            /* show a hamburger */
            .nav-toggle{display:inline-flex;align-items:center;justify-content:center;background:transparent;border:0;color:#fff;font-size:22px;padding:6px 8px;cursor:pointer;right:max(12px, env(safe-area-inset-right));}
            .nav-links{display:none}
            /* when header has .nav-open, show mobile nav as a full-width stacked list */
            header.nav-open .nav-links{display:flex;flex-direction:column;position:absolute;left:0;right:0;top:var(--nav-height);background:var(--accent);padding:12px 16px;gap:8px;box-shadow:0 8px 20px rgba(2,6,23,0.12)}
            header.nav-open .nav-links a{display:block;padding:10px 12px;border-radius:8px;background:transparent;text-align:center}
            /* ensure brand remains visible and container padding reduced */
            .container{padding:0 12px}
            .brand{font-size:16px}
            .main{padding:18px 0}
        }
    </style>
</head>
<body>
    <div class="site-wrapper">
        <header>
            <div class="container navbar">
                <a class="brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
                <button id="navToggle" class="nav-toggle" aria-expanded="false" aria-controls="primary-nav" aria-label="Toggle navigation">☰</button>
                @include('partials.navbar')
            </div>
        </header>

        <main class="container main @yield('main_class')">
            @yield('content')
        </main>

        <footer>
            <div class="container">
                &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }} — Built with Laravel
            </div>
        </footer>
    </div>
    <script>
        (function(){
            var btn = document.getElementById('navToggle');
            var header = document.getElementsByTagName('header')[0];
            var navLinks = document.querySelector('.nav-links');
            if(!btn || !header || !navLinks) return;
            btn.addEventListener('click', function(){
                var open = header.classList.toggle('nav-open');
                btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
            // Close menu when a link is clicked (mobile)
            Array.from(navLinks.querySelectorAll('a')).forEach(function(a){
                a.addEventListener('click', function(){ if(header.classList.contains('nav-open')){ header.classList.remove('nav-open'); btn.setAttribute('aria-expanded','false'); } });
            });
        })();
    </script>
</body>
</html>
