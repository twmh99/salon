<?php
/**
 * Halaman registrasi customer baru.
 */
$pageTitle = 'Registrasi Customer';
require_once __DIR__ . '/../includes/header.php';
$error = flash('error');
$success = flash('success');
?>
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row g-0 auth-card overflow-hidden">
                    <!-- Ilustrasi -->
                    <div class="col-lg-6 d-none d-lg-block auth-illustration" style="background-image:url('https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=80');">
                    </div>
                    <!-- Form registrasi -->
                    <div class="col-lg-6 p-5">
                        <h2 class="mb-3">Mulai Jadi Member DBeauty</h2>
                        <p class="text-muted mb-4">Reservasi lebih cepat, cek status real-time, dan nikmati promo spesial member.</p>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error; ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= $success; ?></div>
                        <?php endif; ?>
                        <form method="post" action="/customer/process/register_process.php">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" placeholder="Mis. Nadine Anjani" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nomor HP</label>
                                <input type="tel" name="nomor_hp" class="form-control" placeholder="08xxxxxxxxxx" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" minlength="6" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" minlength="6" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-rose w-100">Daftar Sekarang</button>
                        </form>
                        <p class="mt-3 mb-0 text-center">Sudah punya akun? <a href="/customer/login.php">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
