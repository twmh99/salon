<?php

/**
 * Attempt to dynamically load SQLite extensions when running via PHP CLI server.
 * This helps avoid "could not find driver" errors when the host PHP runtime
 * does not enable pdo_sqlite/sqlite3 by default.
 */

if (!function_exists('extension_loaded') || !in_array(PHP_SAPI, ['cli', 'cli-server'], true)) {
    return;
}

$candidateDirs = [
    realpath(__DIR__ . '/../vendor/php-extensions/usr/lib/php/20230831'),
    realpath(__DIR__ . '/../../vendor/php-extensions/usr/lib/php/20230831'),
];

$extensions = [];
foreach ($candidateDirs as $candidate) {
    if ($candidate && is_dir($candidate)) {
        $extensions[] = [
            'pdo_sqlite' => $candidate . '/pdo_sqlite.so',
            'sqlite3' => $candidate . '/sqlite3.so',
        ];
    }
}

if (empty($extensions)) {
    return;
}

foreach ($extensions as $group) {
    foreach ($group as $extension => $binaryPath) {
        if (extension_loaded($extension)) {
            continue;
        }

        $enableDynamicLoad = function_exists('dl') && filter_var(ini_get('enable_dl'), FILTER_VALIDATE_BOOLEAN);
        if (!$enableDynamicLoad || !is_file($binaryPath)) {
            error_log(sprintf('[DBeauty Spa] Extension %s tidak tersedia. Pastikan menjalankan server via ./run.sh atau aktifkan ekstensi pada php.ini.', $extension));
            continue;
        }

        try {
            if (!dl($binaryPath)) {
                error_log(sprintf('[DBeauty Spa] Gagal memuat extension %s dari %s', $extension, $binaryPath));
            }
        } catch (\Throwable $e) {
            error_log(sprintf('[DBeauty Spa] Gagal memuat extension %s: %s', $extension, $e->getMessage()));
        }
    }
}
