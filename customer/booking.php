<?php
/**
 * Form booking customer disertai referensi slot jadwal tersedia.
 */
$pageTitle = 'Buat Reservasi';
require_once __DIR__ . '/../includes/functions.php';
requireCustomerAuth();
$treatments = getTreatments();
$success = flash('success');
$error = flash('error');
$sql = "SELECT s.schedule_id, s.tanggal, s.waktu, s.slot, s.jenis_treatment,
        (s.slot - COALESCE(SUM(CASE WHEN r.status IN ('pending','confirmed') THEN 1 ELSE 0 END),0)) AS available_slots
        FROM schedule s
        LEFT JOIN reservasi r ON r.schedule_id = s.schedule_id
        WHERE s.tanggal >= CURDATE()
        GROUP BY s.schedule_id, s.tanggal, s.waktu, s.slot, s.jenis_treatment
        HAVING available_slots > 0
        ORDER BY s.tanggal ASC, s.waktu ASC
        LIMIT 20";
$availableSchedules = getMultipleRows($sql);
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container py-5">
    <div class="dashboard-hero mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <p class="hero-badge mb-1 text-uppercase">Reservasi Treatment</p>
                <h2 class="h4 mb-2">Atur Jadwal Me-Time</h2>
                <p class="mb-0">Pilih treatment yang kamu mau, kemudian kami akan mengunci slot terbaik untukmu.</p>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="treatment-card h-100">
                <h2 class="h4 mb-3">Form Reservasi</h2>
                <?php if ($error): ?><div class="alert alert-danger"><?= $error; ?></div><?php endif; ?>
                <?php if ($success): ?><div class="alert alert-success"><?= $success; ?></div><?php endif; ?>
                <form method="post" action="/customer/process/booking_process.php">
                    <div class="mb-3">
                        <label class="form-label">Pilih Treatment</label>
                        <select class="form-select" name="jenis_treatment" required>
                            <option value="">-- pilih treatment --</option>
                            <?php foreach ($treatments as $item): ?>
                                <option value="<?= $item['id']; ?>"><?= $item['name']; ?> (<?= $item['price']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" min="<?= date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jam</label>
                        <input type="time" class="form-control" name="waktu" required>
                    </div>
                    <button type="submit" class="btn btn-rose w-100">Ajukan Reservasi</button>
                </form>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="treatment-card booking-reference">
                <h2 class="h5 mb-3">Slot Tersedia</h2>
                <p class="text-muted">Lihat jadwal untuk memastikan slot favoritmu masih tersedia.</p>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Treatment</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Sisa Slot</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$availableSchedules): ?>
                                <tr><td colspan="4" class="text-center text-muted">Belum ada jadwal tersedia.</td></tr>
                            <?php else: ?>
                                <?php foreach ($availableSchedules as $slot): ?>
                                    <tr>
                                        <td><?= htmlspecialchars(findTreatment($slot['jenis_treatment'])['name'] ?? $slot['jenis_treatment']); ?></td>
                                        <td><?= date('d M Y', strtotime($slot['tanggal'])); ?></td>
                                        <td><?= date('H:i', strtotime($slot['waktu'])); ?></td>
                                        <td><span class="badge badge-treatment"><?= $slot['available_slots']; ?> slot</span></td>
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
