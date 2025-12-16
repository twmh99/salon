<?php
/**
 * Proses penyimpanan data registrasi customer baru.
 */
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /customer/register.php');
    exit;
}

$nama = sanitize($_POST['nama'] ?? '');
$nomorHp = sanitize($_POST['nomor_hp'] ?? '');
$password = $_POST['password'] ?? '';
$passwordConfirmation = $_POST['password_confirmation'] ?? '';

if (!$nama || !$nomorHp) {
    flash('error', 'Nama dan nomor HP wajib diisi.');
    header('Location: /customer/register.php');
    exit;
}

if ($password !== $passwordConfirmation) {
    flash('error', 'Password dan konfirmasi tidak sama.');
    header('Location: /customer/register.php');
    exit;
}

if (strlen($password) < 6) {
    flash('error', 'Password minimal 6 karakter.');
    header('Location: /customer/register.php');
    exit;
}

$existing = getSingleRow('SELECT customer_id FROM user WHERE nomor_hp = ?', [$nomorHp]);
if ($existing) {
    flash('error', 'Nomor HP sudah terdaftar, gunakan yang lain.');
    header('Location: /customer/register.php');
    exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);
insertData('INSERT INTO user (nama, nomor_hp, password) VALUES (?, ?, ?)', [$nama, $nomorHp, $hash]);
flash('success', 'Registrasi berhasil. Silakan login.');
header('Location: /customer/login.php');
exit;
