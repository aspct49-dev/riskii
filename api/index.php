<?php
/**
 * Front controller. Every page request lands here.
 *
 * Vercel only executes PHP inside api/, so the pages themselves live in
 * pages/ and are pulled in from here. The same file serves local development
 * (router.php) and Apache (.htaccess), so all three behave identically.
 */

require_once __DIR__ . '/../includes/config.php';

// vercel.json passes the original path as ?__route=; locally and on Apache it
// is read straight off the request.
$path = $_GET['__route'] ?? parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = trim((string) $path, '/');
$path = preg_replace('/\.php$/', '', $path);   // old links like /leaderboard.php still work

// Retired URLs that are still linked from old videos, Discord pins and search.
const REDIRECTS = [
    'index'    => '/',
    'duelbits' => '/gamba',     // sponsor before Gamba
    'videos'   => '/',
    'stream'   => '/',
];

const PAGES = [
    ''            => 'home',
    'gamba'       => 'gamba',
    'leaderboard' => 'leaderboard',
    'giveaways'   => 'giveaways',
    'rewards'     => 'rewards',
];

if (isset(REDIRECTS[$path])) {
    header('Location: ' . REDIRECTS[$path], true, 301);
    exit;
}

$page = PAGES[$path] ?? null;

if ($page === null) {
    http_response_code(404);
    header('Cache-Control: public, max-age=0, s-maxage=300');
    require __DIR__ . '/../pages/404.php';
    exit;
}

// Let Vercel's edge cache each page for a minute and keep serving it for five
// more while it refreshes in the background. The countdown runs in the
// browser, so a cached page is never visibly out of date; the standings are at
// most a minute behind Gamba, and the function — and Gamba's API — are hit
// about once a minute per region instead of once per visitor.
header('Cache-Control: public, max-age=0, s-maxage=60, stale-while-revalidate=300');

require __DIR__ . '/../pages/' . $page . '.php';
