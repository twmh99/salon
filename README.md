# DBeauty Skincare & Day Spa (Laravel Edition)

Platform reservasi treatment kecantikan ini kini sepenuhnya ditulis ulang menggunakan **Laravel 12**, sehingga alur customer & admin jauh lebih terstruktur, testable, dan mudah dikembangkan.

## Highlight Fitur
- **Landing page** dengan hero, katalog 31 treatment, langkah reservasi, testimoni, dan informasi kontak menggunakan komponen Bootstrap 5 + stylesheet `public/assets/css/style.css`.
- **Customer Area**
  - Register & login memakai nomor HP.
  - Dashboard ringkas yang menampilkan statistik dan 5 reservasi terakhir.
  - Form booking yang menampilkan slot tersedia secara real-time.
  - Riwayat reservasi lengkap dengan status warna.
- **Admin Area**
  - Login terpisah atau via form login gabungan.
  - Dashboard statistik + tabel reservasi terkini.
  - Kelola jadwal (edit slot, hapus jadwal, ubah data jadwal lewat modal).
  - Kelola reservasi customer (edit jadwal, hapus reservasi).
  - Verifikasi reservasi pending/confirmed beserta pencatatan ke tabel `verifications`.
  - Histori lengkap seluruh reservasi.

Seluruh UI lama dipertahankan sehingga pengalaman pengguna tetap konsisten, namun logic kini memanfaatkan Eloquent, middleware, dan form validation bawaan Laravel.

## Struktur Proyek
- `laravel-app/` – aplikasi Laravel utama (controllers, models, migrations, Blade views).
- `public/assets/` – CSS/JS kustom yang dipakai seluruh halaman.
- `run.sh` – helper script untuk menjalankan `php artisan serve` dengan ekstensi SQLite yang sudah disediakan.
- `database/` – file SQLite lama tetap tersedia apabila ingin melakukan migrasi data manual.

## Persiapan & Menjalankan Aplikasi
1. Pastikan ekstensi SQLite tersedia (sudah disertakan di repo). Jika hilang:
   ```bash
   apt download php8.3-sqlite3
   mkdir -p vendor/php-extensions
   dpkg -x php8.3-sqlite3_*.deb vendor/php-extensions
   ```
2. Masuk ke folder Laravel lalu instal dependensi (jika belum otomatis dibuat):
   ```bash
   cd laravel-app
   composer install
   cp .env.example .env   # sudah dilakukan di repo, jalankan bila butuh ulang
   ```
3. Jalankan migrasi + seeder untuk membuat akun admin default dan jadwal contoh:
   ```bash
   php -d extension=../vendor/php-extensions/usr/lib/php/20230831/pdo_sqlite.so \
       -d extension=../vendor/php-extensions/usr/lib/php/20230831/sqlite3.so \
       artisan migrate --seed
   ```
4. Jika menemui error `could not find driver`, berarti ekstensi SQLite belum aktif di PHP yang menjalankan server. Solusinya:
   - **Linux/macOS**: jalankan server lewat `./run.sh` (otomatis memuat extension). Jika memakai PHP sendiri, aktifkan `extension=pdo_sqlite` dan `extension=sqlite3` di `php.ini`.
   - **Windows (XAMPP/WAMP/custom PHP)**: buka `php.ini`, pastikan baris `extension=pdo_sqlite` dan `extension=sqlite3` tidak diawali `;`, lalu restart server/terminal.
5. Start server dev:
   ```bash
   ./run.sh
   # atau manual: php -d ... artisan serve --host=127.0.0.1 --port=8000
   ```
6. Akses:
   - Customer login/register: `http://localhost:8000/customer/login`
   - Admin login: `http://localhost:8000/admin/login`
   - Landing page: `http://localhost:8000/`

## Akun Default & Data Awal
- **Admin**: `admin / password`
- Jadwal awal mengikuti data seed lama (`ScheduleSeeder`), sehingga pelanggan langsung bisa mencoba booking.

## Testing
Perintah dasar:
```bash
cd laravel-app
php -d extension=../vendor/php-extensions/usr/lib/php/20230831/pdo_sqlite.so \
    -d extension=../vendor/php-extensions/usr/lib/php/20230831/sqlite3.so \
    artisan test
```

## Catatan Pengembangan
- Seluruh helper lama (`getTreatments`, `findTreatment`) kini tersedia melalui `App\Support\TreatmentCatalogue`.
- Middleware `customer.auth` & `admin.auth` menggantikan `requireCustomerAuth()` / `requireAdminAuth()`.
- Semua tampilan berada di `resources/views` dengan layout tunggal `layouts/app.blade.php`, sehingga styling masih identik dengan versi sebelumnya tetapi lebih mudah dipelihara.
