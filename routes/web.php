<?php

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/features', function () {
    return view('features');
})->name('features');

Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

// Dynamic sitemap.xml for basic SEO (lists main public pages)
Route::get('/sitemap.xml', function () {
    $pages = [
        ['loc' => url('/'), 'priority' => '1.0'],
        ['loc' => url('/features'), 'priority' => '0.8'],
        ['loc' => url('/pricing'), 'priority' => '0.8'],
    ];

    $lastmod = now()->toAtomString();

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    foreach ($pages as $p) {
        // XML-escape the absolute URL to avoid malformed XML (e.g. if query strings contain &)
        $loc = htmlspecialchars($p['loc'], ENT_XML1, 'UTF-8');

        $xml .= "  <url>\n";
        $xml .= "    <loc>{$loc}</loc>\n";
        $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
        $xml .= "    <changefreq>weekly</changefreq>\n";
        $xml .= "    <priority>{$p['priority']}</priority>\n";
        $xml .= "  </url>\n";
    }

    $xml .= '</urlset>';

    // Return explicit Content-Type with charset; keep response cookie-free
    return response($xml, 200)
        ->header('Content-Type', 'application/xml; charset=utf-8');

// Disable cookie/session middleware on the route to avoid Set-Cookie headers
})->withoutMiddleware([
    EncryptCookies::class,
    AddQueuedCookiesToResponse::class,
    StartSession::class,
    ShareErrorsFromSession::class,
]);

// Dynamic robots.txt that points to the sitemap
Route::get('/robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Disallow:',
        'Sitemap: ' . url('/sitemap.xml'),
    ];

    return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain']);
});
