<?php
/**
 * Admin – form tambah jadwal dan daftar slot treatment.
 */
$pageTitle = 'Kelola Jadwal';
require_once __DIR__ . '/../includes/functions.php';
requireAdminAuth();
$treatments = getTreatments();
$schedules = getMultipleRows('SELECT s.*, (
        SELECT COUNT(*) FROM reservasi r
        WHERE r.schedule_id = s.schedule_id
          AND r.status IN (\'pending\', \'confirmed\')
    ) AS reserved_count
    FROM schedule s
    ORDER BY s.tanggal ASC, s.waktu ASC
    LIMIT 30');
$reservations = getMultipleRows('SELECT r.*, u.nama, u.nomor_hp, s.tanggal, s.waktu, s.jenis_treatment
    FROM reservasi r
    JOIN user u ON u.customer_id = r.customer_id
    JOIN schedule s ON s.schedule_id = r.schedule_id
    ORDER BY r.reservation_id DESC');
$success = flash('success');
$error = flash('error');
$tab = $_GET['tab'] ?? 'view';
if (!in_array($tab, ['view', 'add'], true)) {
    $tab = 'view';
}
$editReservation = null;
if ($tab === 'add') {
    $reservationId = (int)($_GET['reservation_id'] ?? 0);
    if ($reservationId > 0) {
        $editReservation = getSingleRow('SELECT r.*, u.nama, u.nomor_hp, s.tanggal, s.waktu, s.jenis_treatment
            FROM reservasi r
            JOIN user u ON u.customer_id = r.customer_id
            JOIN schedule s ON s.schedule_id = r.schedule_id
            WHERE r.reservation_id = ?', [$reservationId]);
        if (!$editReservation) {
            flash('error', 'Data reservasi tidak ditemukan.');
        }
    }
}
$activeTask = $tab === 'add' ? 'add_schedule' : 'view_schedule';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-4 col-xl-3">
            <?php include __DIR__ . '/partials/taskbar.php'; ?>
        </div>
        <div class="col-lg-8 col-xl-9">
            <?php if ($tab === 'add'): ?>
                <div class="row g-4">
                    <?php if ($editReservation): ?>
                        <div class="col-lg-5 col-xl-4">
                            <div class="treatment-card h-100 manage-panel" id="schedule-form">
                                <h2 class="h5 mb-3">Edit Jadwal Reservasi Customer</h2>
                                <?php if ($success): ?><div class="alert alert-success"><?= $success; ?></div><?php endif; ?>
                                <?php if ($error): ?><div class="alert alert-danger"><?= $error; ?></div><?php endif; ?>
                                <form method="post" action="/admin/process/reservation_update.php">
                                    <input type="hidden" name="reservation_id" value="<?= $editReservation['reservation_id']; ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Customer</label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($editReservation['nama']); ?> (<?= htmlspecialchars($editReservation['nomor_hp']); ?>)" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jenis Treatment</label>
                                        <select name="jenis_treatment" class="form-select" required>
                                            <?php foreach ($treatments as $t): ?>
                                                <option value="<?= $t['id']; ?>" <?= $editReservation['jenis_treatment'] === $t['id'] ? 'selected' : ''; ?>>
                                                    <?= $t['name']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal</label>
                                        <input type="date" name="tanggal" class="form-control" value="<?= $editReservation['tanggal']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jam</label>
                                        <input type="time" name="waktu" class="form-control" value="<?= $editReservation['waktu']; ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-rose w-100 mb-2">Simpan Jadwal Customer</button>
                                    <a href="/admin/schedules.php?tab=add" class="btn btn-outline-rose w-100">Batalkan Edit</a>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="<?= $editReservation ? 'col-lg-7 col-xl-8' : 'col-12'; ?>">
                        <div class="table-modern p-4" id="schedule-list">
                            <h2 class="h5 mb-3">Daftar Reservasi</h2>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Nomor HP</th>
                                            <th>Treatment</th>
                                            <th>Tanggal</th>
                                            <th>Jam</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!$reservations): ?>
                                            <tr><td colspan="7" class="text-center text-muted">Belum ada reservasi.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($reservations as $reservation): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($reservation['nama']); ?></td>
                                                    <td><?= htmlspecialchars($reservation['nomor_hp']); ?></td>
                                                    <td><?= htmlspecialchars(findTreatment($reservation['jenis_treatment'])['name'] ?? $reservation['jenis_treatment']); ?></td>
                                                    <td><?= date('d M Y', strtotime($reservation['tanggal'])); ?></td>
                                                    <td><?= date('H:i', strtotime($reservation['waktu'])); ?></td>
                                                    <td><span class="badge-status <?= $reservation['status']; ?>"><?= ucfirst($reservation['status']); ?></span></td>
                                                    <td>
                                                        <div class="table-actions justify-content-center">
                                                            <a href="/admin/schedules.php?tab=add&reservation_id=<?= $reservation['reservation_id']; ?>" class="btn btn-action btn-action-edit">Edit</a>
                                                            <form method="post" action="/admin/process/reservation_delete.php" onsubmit="return confirm('Hapus reservasi #<?= $reservation['reservation_id']; ?>?');">
                                                                <input type="hidden" name="reservation_id" value="<?= $reservation['reservation_id']; ?>">
                                                                <button type="submit" class="btn btn-action btn-action-delete">Hapus</button>
                                                            </form>
                                                        </div>
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
            <?php else: ?>
                <div class="table-modern p-4" id="schedule-list">
                    <h2 class="h5 mb-3">Daftar Jadwal</h2>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Treatment</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Terisi</th>
                                    <th>Karyawan (Slot)</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!$schedules): ?>
                                    <tr><td colspan="5" class="text-center text-muted">Belum ada jadwal.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($schedules as $schedule): ?>
                                        <tr>
                                            <td><?= htmlspecialchars(findTreatment($schedule['jenis_treatment'])['name'] ?? $schedule['jenis_treatment']); ?></td>
                                            <td><?= date('d M Y', strtotime($schedule['tanggal'])); ?></td>
                                            <td><span class="time-badge <?= ($schedule['reserved_count'] ?? 0) >= $schedule['slot'] ? 'slot-full' : ''; ?>"><?= date('H:i', strtotime($schedule['waktu'])); ?></span></td>
                                            <td><?= ($schedule['reserved_count'] ?? 0); ?>/<?= $schedule['slot']; ?></td>
                                            <td>
                                                <form method="post" action="/admin/process/schedule_capacity.php" class="d-flex gap-2 align-items-center">
                                                    <input type="hidden" name="schedule_id" value="<?= $schedule['schedule_id']; ?>">
                                                    <input type="number" name="slot" value="<?= $schedule['slot']; ?>" min="1" class="form-control form-control-sm" style="max-width:90px;">
                                                    <button class="btn btn-action btn-action-edit" type="submit">Simpan</button>
                                                </form>
                                            </td>
                                            <td>
                                                <div class="table-actions justify-content-center">
                                                    <button class="btn btn-action btn-action-edit btn-edit-schedule"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editScheduleModal"
                                                        data-id="<?= $schedule['schedule_id']; ?>"
                                                        data-treatment="<?= $schedule['jenis_treatment']; ?>"
                                                        data-date="<?= $schedule['tanggal']; ?>"
                                                        data-time="<?= $schedule['waktu']; ?>"
                                                        data-slot="<?= $schedule['slot']; ?>">
                                                        Edit
                                                    </button>
                                                    <form method="post" action="/admin/process/schedule_delete.php" onsubmit="return confirm('Hapus jadwal ini?');">
                                                        <input type="hidden" name="schedule_id" value="<?= $schedule['schedule_id']; ?>">
                                                        <button class="btn btn-action btn-action-delete" type="submit">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<!-- Modal Edit Jadwal -->
<div class="modal fade" id="editScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="/admin/process/schedule_update.php" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Jadwal Treatment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="schedule_id" id="editScheduleId">
                <div class="mb-3">
                    <label class="form-label">Treatment</label>
                    <select name="jenis_treatment" id="editScheduleTreatment" class="form-select" required>
                        <?php foreach ($treatments as $t): ?>
                            <option value="<?= $t['id']; ?>"><?= $t['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" id="editScheduleDate" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jam</label>
                    <input type="time" name="waktu" id="editScheduleTime" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Slot</label>
                    <input type="number" name="slot" id="editScheduleSlot" class="form-control" min="1" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-rose" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-rose">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.btn-edit-schedule').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('editScheduleId').value = btn.dataset.id;
        document.getElementById('editScheduleDate').value = btn.dataset.date;
        document.getElementById('editScheduleTime').value = btn.dataset.time.substring(0,5);
        document.getElementById('editScheduleSlot').value = btn.dataset.slot;
        const select = document.getElementById('editScheduleTreatment');
        select.value = btn.dataset.treatment;
    });
});
</script>
