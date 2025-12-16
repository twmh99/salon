<?php
/**
 * Proses login gabungan: cek customer terlebih dahulu, jika gagal cek admin.
 */
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /customer/login.php');
    exit;
}

$identifier = sanitize($_POST['identifier'] ?? '');
$password = $_POST['password'] ?? '';

if (!$identifier || !$password) {
    flash('error', 'Masukkan kredensial lengkap.');
    header('Location: /customer/login.php');
    exit;
}

// Cek sebagai customer via nomor HP
$user = getSingleRow('SELECT * FROM user WHERE nomor_hp = ? AND deleted_at IS NULL', [$identifier]);
if ($user && password_verify($password, $user['password'])) {
    $_SESSION['customer'] = [
        'customer_id' => $user['customer_id'],
        'nama' => $user['nama'],
        'nomor_hp' => $user['nomor_hp'],
    ];
    flash('success', 'Selamat datang kembali, ' . $user['nama'] . '!');
    header('Location: /customer/dashboard.php');
    exit;
}

// Jika bukan customer, cek sebagai admin
$admin = getSingleRow('SELECT * FROM admin WHERE username = ?', [$identifier]);
if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin'] = [
        'admin_id' => $admin['admin_id'],
        'username' => $admin['username'],
    ];
    flash('success', 'Berhasil login sebagai admin.');
    header('Location: /admin/dashboard.php');
    exit;
}

flash('error', 'Kredensial tidak valid.');
header('Location: /customer/login.php');
exit;
