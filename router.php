<?php
/**
 * Local development only:  php -S 127.0.0.1:8080 router.php
 *
 * Mirrors vercel.json: real files are served as they are, /includes and
 * /pages are not reachable, and everything else goes to the front controller.
 */
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (preg_match('#^/(includes|pages)(/|$)#', $path)) {
    header('Location: /', true, 302);
    return true;
}

if ($path !== '/' && is_file(__DIR__ . $path) && !str_ends_with($path, '.php')) {
    return false;   // css, js, images, fonts
}

// The JSON endpoint is its own function on Vercel; keep it addressable here.
if (preg_match('#^/api/getGambaLeaderboard(\.php)?$#', $path)) {
    require __DIR__ . '/api/getGambaLeaderboard.php';
    return true;
}

require __DIR__ . '/api/index.php';
return true;
