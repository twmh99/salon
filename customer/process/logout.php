<?php
/**
 * Logout session customer.
 */
require_once __DIR__ . '/../../includes/functions.php';
unset($_SESSION['customer']);
flash('success', 'Anda telah logout.');
header('Location: /');
exit;
