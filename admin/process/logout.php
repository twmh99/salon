<?php
/**
 * Logout admin session.
 */
require_once __DIR__ . '/../../includes/functions.php';
unset($_SESSION['admin']);
flash('success', 'Logout admin berhasil.');
header('Location: /admin/login.php');
exit;
