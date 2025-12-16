#!/usr/bin/env bash
# Helper script to spin up the DBeauty Spa PHP built-in server

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
HOST="${HOST:-127.0.0.1}"
PORT="${PORT:-8000}"
DOCROOT="${ROOT_DIR}"
SCHEMA_FILE="${ROOT_DIR}/database/dbeauty_schema.sql"
EXT_DIR="${ROOT_DIR}/vendor/php-extensions/usr/lib/php/20230831"

if ! command -v php >/dev/null 2>&1; then
    echo "Error: PHP belum terpasang pada PATH. Silakan instal PHP 8+ terlebih dahulu." >&2
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
DBeauty Skincare & Day Spa Dev Server
========================================
- Backend/Frontend: http://${HOST}:${PORT}
- Document root   : ${DOCROOT}
- Default DB      : SQLite -> database/dbeauty.sqlite
- Schema MySQL    : ${SCHEMA_FILE}
  (opsional) Import dengan:
    mysql -u root -p < "${SCHEMA_FILE}"

Tekan Ctrl+C untuk menghentikan server.
INFO

cd "${DOCROOT}"
php -d "extension=${EXT_DIR}/pdo_sqlite.so" \
    -d "extension=${EXT_DIR}/sqlite3.so" \
    -S "${HOST}:${PORT}" -t "${DOCROOT}"
