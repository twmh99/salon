<?php
/**
 * Admin – tampilan histori reservasi.
 */
$pageTitle = 'Histori Reservasi';
require_once __DIR__ . '/../includes/functions.php';
requireAdminAuth();
$history = getMultipleRows('SELECT r.*, u.nama, s.tanggal, s.waktu, s.jenis_treatment FROM reservasi r JOIN user u ON u.customer_id = r.customer_id JOIN schedule s ON s.schedule_id = r.schedule_id ORDER BY r.updated_at DESC, r.reservation_id DESC');
require_once __DIR__ . '/../includes/header.php';
$activeTask = 'history_reservation';
?>
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-4 col-xl-3">
            <?php include __DIR__ . '/partials/taskbar.php'; ?>
        </div>
        <div class="col-lg-8 col-xl-9">
            <div class="dashboard-hero mb-4">
                <h1 class="h4 mb-0">Histori Reservasi</h1>
                <p class="text-muted mb-0">Seluruh perubahan status reservasi ditampilkan berdasarkan waktu terbaru.</p>
            </div>
            <div class="table-modern p-4">
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
                                <th>Terakhir Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$history): ?>
                                <tr><td colspan="7" class="text-center text-muted">Belum ada data histori.</td></tr>
                            <?php else: ?>
                                <?php foreach ($history as $row): ?>
                                    <tr>
                                        <td>#<?= $row['reservation_id']; ?></td>
                                        <td><?= htmlspecialchars($row['nama']); ?></td>
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
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
