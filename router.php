<?php
/**
 * Router for PHP's built-in development server only.
 *
 * Apache does this via .htaccess: try the path, then try it with .php appended.
 * The dev server has no mod_rewrite, so extensionless links like /leaderboard
 * would 404 without this. Harmless in production — it is never invoked there.
 */
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

// Let the server deal with real files (css, js, images) itself.
if ($path !== '/' && is_file($file)) {
    return false;
}

if ($path !== '/' && is_file($file . '.php')) {
    require $file . '.php';
    return true;
}

if ($path === '/' || is_dir($file)) {
    require __DIR__ . '/index.php';
    return true;
}

http_response_code(404);
echo '404 Not Found: ' . htmlspecialchars($path, ENT_QUOTES, 'UTF-8');
return true;
