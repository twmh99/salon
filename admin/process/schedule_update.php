<?php
/**
 * Memperbarui jadwal treatment (nama treatment, tanggal, jam, slot).
 */
require_once __DIR__ . '/../../includes/functions.php';
requireAdminAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/schedules.php?tab=view');
    exit;
}

$scheduleId = (int)($_POST['schedule_id'] ?? 0);
$jenis = sanitize($_POST['jenis_treatment'] ?? '');
$tanggal = sanitize($_POST['tanggal'] ?? '');
$waktu = sanitize($_POST['waktu'] ?? '');
$slot = (int)($_POST['slot'] ?? 0);

if (!$scheduleId || !$jenis || !$tanggal || !$waktu || $slot < 1) {
    flash('error', 'Semua field wajib diisi dengan benar.');
    header('Location: /admin/schedules.php?tab=view');
    exit;
}

$exists = getSingleRow('SELECT schedule_id FROM schedule WHERE schedule_id = ?', [$scheduleId]);
if (!$exists) {
    flash('error', 'Data jadwal tidak ditemukan.');
    header('Location: /admin/schedules.php?tab=view');
    exit;
}

updateData('UPDATE schedule SET jenis_treatment = ?, tanggal = ?, waktu = ?, slot = ? WHERE schedule_id = ?', [$jenis, $tanggal, $waktu, $slot, $scheduleId]);
flash('success', 'Jadwal berhasil diperbarui.');
header('Location: /admin/schedules.php?tab=view');
exit;
