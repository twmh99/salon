<?php
/**
 * Menerima permintaan booking customer dan menyimpannya sebagai reservasi pending.
 */
require_once __DIR__ . '/../../includes/functions.php';
requireCustomerAuth();
$customer = authCustomer();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /customer/booking.php');
    exit;
}

$jenis = sanitize($_POST['jenis_treatment'] ?? '');
$tanggal = sanitize($_POST['tanggal'] ?? '');
$waktu = sanitize($_POST['waktu'] ?? '');

if (!findTreatment($jenis)) {
    flash('error', 'Jenis treatment tidak ditemukan.');
    header('Location: /customer/booking.php');
    exit;
}

if (!$tanggal || !$waktu) {
    flash('error', 'Tanggal dan waktu harus diisi.');
    header('Location: /customer/booking.php');
    exit;
}

$schedule = getSingleRow('SELECT * FROM schedule WHERE jenis_treatment = ? AND tanggal = ? AND waktu = ?', [$jenis, $tanggal, $waktu]);
if (!$schedule) {
    flash('error', 'Slot jadwal belum tersedia. Silakan hubungi admin.');
    header('Location: /customer/booking.php');
    exit;
}

$booked = getSingleRow("SELECT COUNT(*) AS total FROM reservasi WHERE schedule_id = ? AND status IN ('pending','confirmed')", [$schedule['schedule_id']]);
$available = ($schedule['slot'] ?? 0) - ($booked['total'] ?? 0);
if ($available <= 0) {
    flash('error', 'Slot telah penuh, coba pilih jadwal lain.');
    header('Location: /customer/booking.php');
    exit;
}

insertData('INSERT INTO reservasi (customer_id, schedule_id, status) VALUES (?, ?, ?)', [$customer['customer_id'], $schedule['schedule_id'], 'pending']);
flash('success', 'Reservasi berhasil diajukan dan menunggu verifikasi admin.');
header('Location: /customer/my-reservations.php');
exit;
