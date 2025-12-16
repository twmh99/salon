<?php
/**
 * Dashboard customer – ringkasan reservasi dan quick action.
 */
$pageTitle = 'Dashboard Customer';
require_once __DIR__ . '/../includes/functions.php';
requireCustomerAuth();
$customer = authCustomer();

$totalCount = fetchCount('SELECT COUNT(*) AS total FROM reservasi WHERE customer_id = ?', [$customer['customer_id']]);
$pendingCount = fetchCount("SELECT COUNT(*) AS total FROM reservasi WHERE customer_id = ? AND status = 'pending'", [$customer['customer_id']]);
$confirmedCount = fetchCount("SELECT COUNT(*) AS total FROM reservasi WHERE customer_id = ? AND status = 'confirmed'", [$customer['customer_id']]);
$reservations = getMultipleRows('SELECT r.*, s.tanggal, s.waktu, s.jenis_treatment FROM reservasi r JOIN schedule s ON r.schedule_id = s.schedule_id WHERE r.customer_id = ? ORDER BY s.tanggal ASC, s.waktu ASC LIMIT 5', [$customer['customer_id']]);
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container py-5">
    <div class="dashboard-hero mb-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <p class="hero-badge mb-1 text-uppercase">Selamat Datang</p>
                <h1 class="h3 mb-2"><?= htmlspecialchars($customer['nama']); ?></h1>
                <p class="mb-0">Atur jadwal treatment, pantau status reservasi, dan nikmati promo khusus member.</p>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-rose" href="/customer/booking.php">Buat Reservasi</a>
                <a class="btn btn-outline-rose" href="/customer/my-reservations.php">Riwayat</a>
            </div>
        </div>
    </div>
    <div class="row g-3 mb-5">
        <div class="col-md-4">
            <div class="stat-card text-center p-4">
                <p class="text-muted mb-1">Total Reservasi</p>
                <h3><?= $totalCount; ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card text-center p-4">
                <p class="text-muted mb-1">Menunggu Verifikasi</p>
                <h3><?= $pendingCount; ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card text-center p-4">
                <p class="text-muted mb-1">Sudah Dikonfirmasi</p>
                <h3><?= $confirmedCount; ?></h3>
            </div>
        </div>
    </div>
    <div class="table-modern p-4">
        <h3 class="h5 mb-3">Reservasi Terbaru</h3>
        <?php if (!$reservations): ?>
            <p class="text-muted mb-0">Belum ada reservasi. Klik "Buat Reservasi" untuk mulai.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Treatment</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars(findTreatment($row['jenis_treatment'])['name'] ?? $row['jenis_treatment']); ?></td>
                                <td><?= date('d M Y', strtotime($row['tanggal'])); ?></td>
                                <td><?= date('H:i', strtotime($row['waktu'])); ?></td>
                                <td><span class="badge-status <?= $row['status']; ?>"><?= ucfirst($row['status']); ?></span></td>
                                <td><?= date('d M Y H:i', strtotime($row['reservation_date'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
