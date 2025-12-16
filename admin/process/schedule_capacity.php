<?php
/**
 * Mengatur jumlah karyawan (slot) per jadwal.
 */
require_once __DIR__ . '/../../includes/functions.php';
requireAdminAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/schedules.php?tab=view');
    exit;
}

$scheduleId = (int)($_POST['schedule_id'] ?? 0);
$slot = (int)($_POST['slot'] ?? 0);

if (!$scheduleId || $slot < 1) {
    flash('error', 'Slot harus lebih dari 0.');
    header('Location: /admin/schedules.php?tab=view');
    exit;
}

$schedule = getSingleRow('SELECT schedule_id FROM schedule WHERE schedule_id = ?', [$scheduleId]);
if (!$schedule) {
    flash('error', 'Data jadwal tidak ditemukan.');
    header('Location: /admin/schedules.php?tab=view');
    exit;
}

updateData('UPDATE schedule SET slot = ? WHERE schedule_id = ?', [$slot, $scheduleId]);
flash('success', 'Jumlah karyawan diperbarui.');
header('Location: /admin/schedules.php?tab=view');
exit;
