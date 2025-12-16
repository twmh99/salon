<?php
/**
 * Tampilan error global untuk menampilkan pesan ramah pengguna.
 */
$pageTitle = 'Kesalahan Sistem';
require_once __DIR__ . '/header.php';
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center p-5 bg-white shadow rounded-4">
                <h1 class="display-6 text-danger mb-3">Oops!</h1>
                <p class="lead mb-4"><?= htmlspecialchars($errorMessage ?? 'Terjadi kesalahan.'); ?></p>
                <p class="text-muted mb-4">Pastikan database sudah diimpor dan konfigurasi koneksi benar, lalu coba kembali.</p>
                <a href="/" class="btn btn-primary">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
