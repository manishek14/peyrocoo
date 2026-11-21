<?php
/**
 * Router for PHP built-in server (php -S 127.0.0.1:8080 router.php)
 * Replicates the behaviour of the site's .htaccess:
 * - existing static files are served directly
 * - everything else goes through index.php (Laravel front controller)
 */
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = __DIR__ . $uri;

// Block private directories, mirroring the .htaccess guard
// (panel/.env and other tooling files must never be downloadable)
if (preg_match('#^/(\.git|\.github|panel|docker|scripts|tests)(/|$)#', $uri)) {
    http_response_code(403);
    exit('Forbidden');
}

if ($uri !== '/' && file_exists($file) && !is_dir($file)) {
    return false; // let the built-in server serve the static file
}

$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/index.php';
