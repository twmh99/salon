<?php
/**
 * Dashboard admin untuk memantau statistik dan reservasi terbaru.
 */
$pageTitle = 'Dashboard Admin';
require_once __DIR__ . '/../includes/functions.php';
requireAdminAuth();
$stats = [
    'customers' => fetchCount('SELECT COUNT(*) AS total FROM user WHERE deleted_at IS NULL'),
    'pending' => fetchCount("SELECT COUNT(*) AS total FROM reservasi WHERE status = 'pending'"),
    'confirmed' => fetchCount("SELECT COUNT(*) AS total FROM reservasi WHERE status = 'confirmed'"),
    'schedules' => fetchCount('SELECT COUNT(*) AS total FROM schedule'),
];
$recentReservations = getMultipleRows('SELECT r.*, u.nama, s.tanggal, s.waktu, s.jenis_treatment FROM reservasi r JOIN user u ON u.customer_id = r.customer_id JOIN schedule s ON s.schedule_id = r.schedule_id ORDER BY r.reservation_id DESC LIMIT 10');
require_once __DIR__ . '/../includes/header.php';
$activeTask = '';
?>
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-4 col-xl-3">
            <?php include __DIR__ . '/partials/taskbar.php'; ?>
        </div>
        <div class="col-lg-8 col-xl-9">
            <div class="dashboard-hero mb-5">
                <h1 class="h3">Dashboard Admin</h1>
                <p class="mb-0 text-muted">Monitor performa reservasi, jadwal, dan approval dalam satu tampilan elegan.</p>
            </div>
            <div class="row g-3 mb-5">
                <div class="col-md-3">
                    <div class="stat-card text-center p-4">
                        <p class="text-muted">Customer</p>
                        <h3><?= $stats['customers']; ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card text-center p-4">
                        <p class="text-muted">Pending</p>
                        <h3><?= $stats['pending']; ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card text-center p-4">
                        <p class="text-muted">Confirmed</p>
                        <h3><?= $stats['confirmed']; ?></h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card text-center p-4">
                        <p class="text-muted">Jadwal</p>
                        <h3><?= $stats['schedules']; ?></h3>
                    </div>
                </div>
            </div>
            <div class="table-modern p-4">
                <h3 class="h5 mb-3">Reservasi Terbaru</h3>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Treatment</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$recentReservations): ?>
                                <tr><td colspan="6" class="text-center text-muted">Belum ada reservasi.</td></tr>
                            <?php else: ?>
                                <?php foreach ($recentReservations as $row): ?>
                                    <tr>
                                        <td>#<?= $row['reservation_id']; ?></td>
                                        <td><?= htmlspecialchars($row['nama']); ?></td>
                                        <td><?= htmlspecialchars(findTreatment($row['jenis_treatment'])['name'] ?? $row['jenis_treatment']); ?></td>
                                        <td><?= date('d M Y', strtotime($row['tanggal'])); ?></td>
                                        <td><?= date('H:i', strtotime($row['waktu'])); ?></td>
                                        <td><span class="badge-status <?= $row['status']; ?>"><?= ucfirst($row['status']); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
