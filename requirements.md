# DBeauty Skincare & Day Spa - Website Reservasi

## Tujuan Utama
Mempermudah dan mempercepat sistem reservasi atau booking treatment kecantikan

## Teknologi
- Backend: PHP
- Database: MySQL
- Frontend: HTML, CSS, Bootstrap
- Struktur: MVC sederhana

## Database Schema
1. **Table `user`:**
   - customer_id (PK)
   - nama
   - nomor_hp
   - password
   - created_at
   - deleted_at
   - updated_at

2. **Table `reservasi`:**
   - reservation_id (PK)
   - customer_id (FK)
   - schedule_id (FK)
   - reservation_date
   - status

3. **Table `schedule`:**
   - schedule_id (PK)
   - tanggal
   - waktu
   - slot
   - jenis_treatment

4. **Table `verifikasi`:**
   - verification_id (PK)
   - reservation_id (FK)
   - admin_id (FK)
   - tanggal_verifikasi
   - status

5. **Table `admin`:**
   - admin_id (PK)
   - username
   - password

## User Roles
### Customer
- Registrasi dan login
- Memilih jenis treatment
- Memilih tanggal dan jam treatment
- Melakukan reservasi
- Melihat status reservasi

### Admin
- Login ke dashboard admin
- Melihat dan mengedit jadwal treatment
- Melakukan verifikasi reservasi

## Fitur Utama
- Landing page sederhana
- Sistem registrasi/login customer dan admin
- Dashboard untuk customer
- Dashboard admin
- Sistem reservasi treatment
- Verifikasi reservasi oleh admin
- Responsif dengan Bootstrap
