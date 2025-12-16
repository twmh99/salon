<?php
/**
 * Menampilkan daftar lengkap reservasi milik customer.
 */
$pageTitle = 'Riwayat Reservasi';
require_once __DIR__ . '/../includes/functions.php';
requireCustomerAuth();
$customer = authCustomer();
$rows = getMultipleRows('SELECT r.*, s.tanggal, s.waktu, s.jenis_treatment FROM reservasi r JOIN schedule s ON r.schedule_id = s.schedule_id WHERE r.customer_id = ? ORDER BY r.reservation_id DESC', [$customer['customer_id']]);
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container py-5">
    <div class="dashboard-hero mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <p class="hero-badge mb-1">Riwayat Reservasi</p>
            <h1 class="h4 mb-0">Pantau Semua Reservasi Kamu</h1>
            <p class="mb-0 text-muted">Status real-time dengan highlight warna agar mudah dibaca.</p>
        </div>
        <a href="/customer/booking.php" class="btn btn-rose">Reservasi Baru</a>
    </div>
    <div class="table-modern p-4">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Treatment</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Status</th>
                        <th>Terakhir Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$rows): ?>
                        <tr><td colspan="6" class="text-center text-muted">Belum ada data reservasi.</td></tr>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?= $row['reservation_id']; ?></td>
                                <td><?= htmlspecialchars(findTreatment($row['jenis_treatment'])['name'] ?? $row['jenis_treatment']); ?></td>
                                <td><?= date('d M Y', strtotime($row['tanggal'])); ?></td>
                                <td><?= date('H:i', strtotime($row['waktu'])); ?></td>
                                <td><span class="badge-status <?= $row['status']; ?>"><?= ucfirst($row['status']); ?></span></td>
                                <td><?= date('d M Y H:i', strtotime($row['updated_at'] ?? $row['reservation_date'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
