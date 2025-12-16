-- DBeauty Skincare & Day Spa Schema
CREATE DATABASE IF NOT EXISTS dbeauty_spa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dbeauty_spa;

DROP TABLE IF EXISTS verifikasi;
DROP TABLE IF EXISTS reservasi;
DROP TABLE IF EXISTS schedule;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS admin;

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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_schedule (tanggal, waktu, jenis_treatment)
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

INSERT INTO admin (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO schedule (tanggal, waktu, slot, jenis_treatment) VALUES
('2024-08-01', '09:00:00', 3, 'HC1'),
('2024-08-01', '11:00:00', 3, 'HC7'),
('2024-08-01', '13:00:00', 2, 'HC23'),
('2024-08-02', '10:00:00', 3, 'HC4'),
('2024-08-02', '14:00:00', 3, 'HC10'),
('2024-08-02', '16:00:00', 2, 'HC20'),
('2024-08-03', '09:00:00', 4, 'HC13'),
('2024-08-03', '11:30:00', 2, 'HC19'),
('2024-08-03', '15:00:00', 3, 'HC30');

CREATE INDEX idx_reservasi_customer ON reservasi(customer_id);
CREATE INDEX idx_reservasi_schedule ON reservasi(schedule_id);
CREATE INDEX idx_schedule_date ON schedule(tanggal);
CREATE INDEX idx_verifikasi_reservation ON verifikasi(reservation_id);
