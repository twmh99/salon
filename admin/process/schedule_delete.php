<?php
/**
 * Menghapus jadwal treatment oleh admin.
 */
require_once __DIR__ . '/../../includes/functions.php';
requireAdminAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/schedules.php?tab=view');
    exit;
}

$scheduleId = (int)($_POST['schedule_id'] ?? 0);
if (!$scheduleId) {
    flash('error', 'ID jadwal tidak valid.');
    header('Location: /admin/schedules.php?tab=view');
    exit;
}

$exists = getSingleRow('SELECT schedule_id FROM schedule WHERE schedule_id = ?', [$scheduleId]);
if (!$exists) {
    flash('error', 'Data jadwal tidak ditemukan.');
    header('Location: /admin/schedules.php?tab=view');
    exit;
}

deleteData('DELETE FROM schedule WHERE schedule_id = ?', [$scheduleId]);
flash('success', 'Jadwal berhasil dihapus.');
header('Location: /admin/schedules.php?tab=view');
exit;
