<?php
/**
 * Memperbarui status reservasi dan menyimpan catatan verifikasi admin.
 */
require_once __DIR__ . '/../../includes/functions.php';
requireAdminAuth();
$admin = authAdmin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/verify.php');
    exit;
}
$reservationId = (int)($_POST['reservation_id'] ?? 0);
$status = $_POST['status'] ?? '';
if (!$reservationId || !in_array($status, ['confirmed', 'rejected'], true)) {
    flash('error', 'Parameter verifikasi tidak valid.');
    header('Location: /admin/verify.php');
    exit;
}
$reservation = getSingleRow('SELECT * FROM reservasi WHERE reservation_id = ?', [$reservationId]);
if (!$reservation) {
    flash('error', 'Reservasi tidak ditemukan.');
    header('Location: /admin/verify.php');
    exit;
}
$reservationStatus = $status === 'confirmed' ? 'confirmed' : 'cancelled';
$verificationStatus = $status === 'confirmed' ? 'approved' : 'rejected';
updateData('UPDATE reservasi SET status = ?, updated_at = NOW() WHERE reservation_id = ?', [$reservationStatus, $reservationId]);
insertData('INSERT INTO verifikasi (reservation_id, admin_id, status) VALUES (?, ?, ?)', [$reservationId, $admin['admin_id'], $verificationStatus]);
flash('success', 'Reservasi #' . $reservationId . ' diperbarui menjadi ' . $reservationStatus . '.');
header('Location: /admin/verify.php');
exit;
