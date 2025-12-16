<?php
/**
 * Komponen taskbar/menu untuk area admin.
 * Pastikan requireAdminAuth() sudah dipanggil sebelum include file ini.
 */
$adminUser = authAdmin();
$activeTask = $activeTask ?? '';
$taskItems = [
    [
        'key' => 'view_schedule',
        'label' => 'Lihat Jadwal Reservasi',
        'href' => '/admin/schedules.php?tab=view',
    ],
    [
        'key' => 'add_schedule',
        'label' => 'Kelola Jadwal',
        'href' => '/admin/schedules.php?tab=add',
    ],
    [
        'key' => 'history_reservation',
        'label' => 'Lihat Histori Reservasi',
        'href' => '/admin/history.php',
    ],
];
?>
<aside class="admin-taskbar">
    <div class="taskbar-header">
        <p class="mb-1 text-muted small">Admin</p>
        <strong><?= htmlspecialchars($adminUser['username'] ?? ''); ?></strong>
    </div>
    <div class="taskbar-menu mt-4">
        <?php foreach ($taskItems as $item): ?>
            <a class="btn taskbar-btn <?= $activeTask === $item['key'] ? 'active' : ''; ?>" href="<?= $item['href']; ?>">
                <?= $item['label']; ?>
            </a>
        <?php endforeach; ?>
    </div>
    <div class="mt-4 pt-3 border-top border-light">
        <a href="/admin/process/logout.php" class="btn btn-outline-rose w-100">Log Out</a>
    </div>
</aside>
