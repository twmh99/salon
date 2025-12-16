# DBeauty Skincare & Day Spa

Platform reservasi treatment kecantikan yang mempermudah pelanggan melakukan pemesanan treatment rambut & spa secara daring, sekaligus menyediakan panel admin untuk mengelola jadwal & verifikasi reservasi.

## Rancangan Landing Page
1. **Hero Section** – headline singkat, CTA "Booking Sekarang" yang menuju form reservasi/login dan tombol "Lihat Treatment".
2. **Highlight Features** – tiga kartu ringkas (Treatment Profesional, Jadwal Fleksibel, Sistem Verifikasi Aman).
3. **Daftar Treatment & Harga** – tabel responsif yang menampilkan seluruh 31 pilihan treatment berdasarkan data hair care.
4. **Langkah Reservasi** – alur 3 langkah (Registrasi, Pilih Jadwal, Nikmati Treatment).
5. **Testimonial Singkat** – placeholder untuk ulasan pelanggan.
6. **CTA Footer** – tombol menuju registrasi, informasi kontak, dan link ke dashboard admin.

Semua section memanfaatkan komponen Bootstrap 5 agar cepat responsif, dipadukan dengan aksen warna pastel (#f9dede & #ebf7f5) supaya selaras dengan identitas spa.

## Struktur Navigasi
- **Global (Header)**: Home, Treatment, Langkah Booking, Kontak. CTA khusus tergantung role.
- **Customer**:
  - Register (`/customer/register.php`)
  - Login (`/customer/login.php`)
  - Dashboard (`/customer/dashboard.php`)
  - Booking Baru (`/customer/booking.php`)
  - Riwayat Reservasi (`/customer/my-reservations.php`)
  - Logout
- **Admin**:
  - Login (`/admin/login.php`)
  - Dashboard (`/admin/dashboard.php`)
  - Kelola Jadwal (`/admin/schedules.php`)
  - Verifikasi Reservasi (`/admin/verify.php`)
  - Logout

Navigasi customer & admin diletakkan dalam menu dropdown pada navbar ketika user sudah login sesuai role.

## Skema Database MySQL
```sql
CREATE TABLE admin (
    admin_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE user (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    nomor_hp VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

CREATE TABLE schedule (
    schedule_id INT PRIMARY KEY AUTO_INCREMENT,
    tanggal DATE NOT NULL,
    waktu TIME NOT NULL,
    slot INT NOT NULL DEFAULT 1,
    jenis_treatment VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE reservasi (
    reservation_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    schedule_id INT NOT NULL,
    reservation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending','confirmed','cancelled','completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES user(customer_id) ON DELETE CASCADE,
    FOREIGN KEY (schedule_id) REFERENCES schedule(schedule_id) ON DELETE CASCADE
);

CREATE TABLE verifikasi (
    verification_id INT PRIMARY KEY AUTO_INCREMENT,
    reservation_id INT NOT NULL,
    admin_id INT NOT NULL,
    tanggal_verifikasi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('approved','rejected') NOT NULL,
    notes TEXT,
    FOREIGN KEY (reservation_id) REFERENCES reservasi(reservation_id) ON DELETE CASCADE,
    FOREIGN KEY (admin_id) REFERENCES admin(admin_id) ON DELETE CASCADE
);
```

## Backend & Frontend Awal
- **Konfigurasi**: `config/database.php` untuk koneksi PDO, `config/config.php` memuat konstanta aplikasi & helper global.
- **Helper**: `includes/functions.php` berisi sanitasi input, flash message, pengecekan sesi dan utilitas booking.
- **Data Treatment**: `includes/treatments.php` menyimpan array 31 treatment agar bisa dipakai landing page maupun dropdown booking.
- **Customer Flow**: Form register & login, dashboard sederhana, form booking, daftar reservasi, serta proses backend (insert user, autentikasi, simpan reservasi, cek slot schedule).
- **Admin Flow**: Login, dashboard ringkas menampilkan statistik, halaman kelola jadwal (CRUD dasar slot) dan verifikasi reservasi.
- **Frontend**: Template header/footer + Bootstrap 5 CDN, satu file CSS (`assets/css/style.css`) dan JS ringan (`assets/js/main.js`).

## Mockup Referensi
- `mockups/landing-mockup.html` – wireframe cepat yang menunjukkan struktur hero, kartu treatment, testimoni, dan map pada landing page.
- `mockups/booking-mockup.html` – visual kasar alur halaman reservasi lengkap dengan form dan tabel slot tersedia.

## Cara Pakai Singkat
1. Sekali saja jalankan:
   ```
   apt download php8.3-sqlite3
   mkdir -p vendor/php-extensions
   dpkg -x php8.3-sqlite3_*.deb vendor/php-extensions
   ```
   (perintah tersebut sudah dilakukan di repo ini; jalankan lagi hanya bila folder hilang).
2. Secara default aplikasi memakai SQLite (`database/dbeauty.sqlite`). Tidak perlu setup MySQL, cukup jalankan `./run.sh` agar server memakai konfigurasi & extension yang tepat.
3. Jika ingin MySQL, import schema via `database/dbeauty_schema.sql` dan ubah `DB_DRIVER` di `config/database.php` menjadi `mysql`.
4. Sesuaikan kredensial database di `config/database.php` bila perlu.
5. Jalankan proyek pada server PHP (`./run.sh` dianjurkan karena otomatis memuat extension SQLite).
4. Registrasi customer baru, lakukan login, lalu pilih jadwal lewat halaman booking.
5. Login admin dengan akun default (`admin` / `password`) untuk verifikasi reservasi.

Dokumentasi ringkas juga tersedia di setiap file utama melalui komentar blok untuk memudahkan pengembangan lanjutan.
