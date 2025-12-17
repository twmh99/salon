#!/usr/bin/env bash
# Helper script to spin up the DBeauty Spa PHP built-in server

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APP_DIR="${ROOT_DIR}/laravel-app"
HOST="${HOST:-127.0.0.1}"
PORT="${PORT:-8000}"
EXT_DIR="${ROOT_DIR}/vendor/php-extensions/usr/lib/php/20230831"

if ! command -v php >/dev/null 2>&1; then
    echo "Error: PHP belum terpasang pada PATH. Silakan instal PHP 8+ terlebih dahulu." >&2
    exit 1
fi

if [ ! -f "${APP_DIR}/artisan" ]; then
    echo "Error: Proyek Laravel belum ditemukan di ${APP_DIR}. Pastikan folder laravel-app tersedia." >&2
    exit 1
fi

if [ ! -f "${EXT_DIR}/pdo_sqlite.so" ] || [ ! -f "${EXT_DIR}/sqlite3.so" ]; then
    cat >&2 <<'ERR'
Error: Extension SQLite untuk PHP belum tersedia.
Jalankan sekali perintah berikut di root proyek:
    apt download php8.3-sqlite3
    mkdir -p vendor/php-extensions
    dpkg -x php8.3-sqlite3_*.deb vendor/php-extensions
ERR
    exit 1
fi

cat <<INFO
========================================
DBeauty Skincare & Day Spa (Laravel) Dev Server
========================================
- URL Aplikasi : http://${HOST}:${PORT}
- Direktori App: ${APP_DIR}
- Database     : ${APP_DIR}/database/database.sqlite
Tekan Ctrl+C untuk menghentikan server.
INFO

cd "${APP_DIR}"
php -d "extension=${EXT_DIR}/pdo_sqlite.so" \
    -d "extension=${EXT_DIR}/sqlite3.so" \
    artisan serve --host="${HOST}" --port="${PORT}"
