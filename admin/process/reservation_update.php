<?php
/**
 * Mengubah jadwal/treatment reservasi customer oleh admin.
 */
require_once __DIR__ . '/../../includes/functions.php';
requireAdminAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/schedules.php?tab=add');
    exit;
}

$reservationId = (int)($_POST['reservation_id'] ?? 0);
$jenis = sanitize($_POST['jenis_treatment'] ?? '');
$tanggal = sanitize($_POST['tanggal'] ?? '');
$waktu = sanitize($_POST['waktu'] ?? '');

if (!$reservationId || !$jenis || !$tanggal || !$waktu) {
    flash('error', 'Semua field wajib diisi.');
    header('Location: /admin/schedules.php?tab=add');
    exit;
}

$reservation = getSingleRow('SELECT * FROM reservasi WHERE reservation_id = ?', [$reservationId]);
if (!$reservation) {
    flash('error', 'Reservasi tidak ditemukan.');
    header('Location: /admin/schedules.php?tab=add');
    exit;
}

// Cari jadwal yang sesuai atau buat baru
$schedule = getSingleRow('SELECT * FROM schedule WHERE jenis_treatment = ? AND tanggal = ? AND waktu = ?', [$jenis, $tanggal, $waktu]);
if ($schedule) {
    $scheduleId = $schedule['schedule_id'];
    $slot = (int)$schedule['slot'];
} else {
    $scheduleId = insertData('INSERT INTO schedule (tanggal, waktu, slot, jenis_treatment) VALUES (?, ?, ?, ?)', [$tanggal, $waktu, 3, $jenis]);
    $slot = 3;
}

$booked = getSingleRow("SELECT COUNT(*) AS total FROM reservasi WHERE schedule_id = ? AND status IN ('pending','confirmed') AND reservation_id != ?", [$scheduleId, $reservationId]);
if (($booked['total'] ?? 0) >= $slot) {
    flash('error', 'Slot jadwal penuh, pilih jadwal lainnya.');
    header('Location: /admin/schedules.php?tab=add&reservation_id=' . $reservationId);
    exit;
}

updateData('UPDATE reservasi SET schedule_id = ?, updated_at = CURRENT_TIMESTAMP WHERE reservation_id = ?', [$scheduleId, $reservationId]);
flash('success', 'Reservasi #' . $reservationId . ' berhasil diperbarui.');
header('Location: /admin/schedules.php?tab=add');
exit;
