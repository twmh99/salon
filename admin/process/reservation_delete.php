<?php
/**
 * Menghapus reservasi customer oleh admin.
 */
require_once __DIR__ . '/../../includes/functions.php';
requireAdminAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/schedules.php?tab=add');
    exit;
}

$reservationId = (int)($_POST['reservation_id'] ?? 0);
if (!$reservationId) {
    flash('error', 'Data reservasi tidak valid.');
    header('Location: /admin/schedules.php?tab=add');
    exit;
}

$reservation = getSingleRow('SELECT reservation_id FROM reservasi WHERE reservation_id = ?', [$reservationId]);
if (!$reservation) {
    flash('error', 'Reservasi tidak ditemukan.');
    header('Location: /admin/schedules.php?tab=add');
    exit;
}

deleteData('DELETE FROM reservasi WHERE reservation_id = ?', [$reservationId]);
flash('success', 'Reservasi #' . $reservationId . ' berhasil dihapus.');
header('Location: /admin/schedules.php?tab=add');
exit;
