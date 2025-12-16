<?php
/**
 * Proses autentikasi admin.
 */
require_once __DIR__ . '/../../includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/login.php');
    exit;
}
$username = sanitize($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$admin = getSingleRow('SELECT * FROM admin WHERE username = ?', [$username]);
if (!$admin || !password_verify($password, $admin['password'])) {
    flash('error', 'Username atau password salah.');
    header('Location: /admin/login.php');
    exit;
}
$_SESSION['admin'] = [
    'admin_id' => $admin['admin_id'],
    'username' => $admin['username'],
];
header('Location: /admin/dashboard.php');
exit;
