<?php
/**
 * App Configuration
 * Menyimpan konfigurasi global aplikasi serta bootstrap session.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_NAME', 'DBeauty Skincare & Day Spa');
define('APP_ENV', 'development');

date_default_timezone_set('Asia/Jakarta');

// Muat koneksi database
require_once __DIR__ . '/database.php';

/**
 * Helper untuk membuat URL absolut berbasis request saat ini.
 * Digunakan untuk link dinamis pada template.
 */
function build_url(string $path = ''): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $baseDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    $base = $scheme . $host . ($baseDir ? $baseDir : '');
    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

set_exception_handler(function (Throwable $throwable) {
    error_log('[APP ERROR] ' . $throwable->getMessage());

    if (PHP_SAPI === 'cli') {
        fwrite(STDERR, "ERROR: " . $throwable->getMessage() . PHP_EOL);
        exit(1);
    }

    http_response_code(500);
    $errorMessage = APP_ENV === 'development'
        ? $throwable->getMessage()
        : 'Maaf, terjadi kesalahan pada sistem. Silakan coba lagi nanti.';

    include __DIR__ . '/../includes/error.php';
    exit;
});
