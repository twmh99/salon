<?php
/**
 * Admin – verifikasi reservasi pending atau confirmed.
 */
$pageTitle = 'Verifikasi Reservasi';
require_once __DIR__ . '/../includes/functions.php';
requireAdminAuth();
$pending = getMultipleRows("SELECT r.*, u.nama, s.tanggal, s.waktu, s.jenis_treatment FROM reservasi r JOIN user u ON u.customer_id = r.customer_id JOIN schedule s ON s.schedule_id = r.schedule_id WHERE r.status IN ('pending','confirmed') ORDER BY r.reservation_id ASC");
$success = flash('success');
$error = flash('error');
$activeTask = '';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-4 col-xl-3">
            <?php include __DIR__ . '/partials/taskbar.php'; ?>
        </div>
        <div class="col-lg-8 col-xl-9">
            <div class="dashboard-hero mb-4">
                <h1 class="h4 mb-0">Verifikasi Reservasi</h1>
                <p class="text-muted mb-0">Setujui atau tolak permintaan pelanggan dengan sekali klik.</p>
            </div>
            <?php if ($success): ?><div class="alert alert-success"><?= $success; ?></div><?php endif; ?>
            <?php if ($error): ?><div class="alert alert-danger"><?= $error; ?></div><?php endif; ?>
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
                                <th>Status Saat Ini</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$pending): ?>
                                <tr><td colspan="7" class="text-center text-muted">Tidak ada reservasi untuk diverifikasi.</td></tr>
                            <?php else: ?>
                                <?php foreach ($pending as $row): ?>
                                    <tr>
                                        <td>#<?= $row['reservation_id']; ?></td>
                                        <td><?= htmlspecialchars($row['nama']); ?></td>
                                        <td><?= htmlspecialchars(findTreatment($row['jenis_treatment'])['name'] ?? $row['jenis_treatment']); ?></td>
                                        <td><?= date('d M Y', strtotime($row['tanggal'])); ?></td>
                                        <td><?= date('H:i', strtotime($row['waktu'])); ?></td>
                                        <td><span class="badge-status <?= $row['status']; ?>"><?= ucfirst($row['status']); ?></span></td>
                                        <td>
                                            <form method="post" action="/admin/process/verify_process.php" class="d-flex gap-2">
                                                <input type="hidden" name="reservation_id" value="<?= $row['reservation_id']; ?>">
                                                <input type="hidden" name="current_status" value="<?= $row['status']; ?>">
                                                <button name="status" value="confirmed" class="btn btn-success btn-sm" <?= $row['status'] === 'confirmed' ? 'disabled' : ''; ?>>Approve</button>
                                                <button name="status" value="rejected" class="btn btn-danger btn-sm">Reject</button>
                                            </form>
                                        </td>
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
