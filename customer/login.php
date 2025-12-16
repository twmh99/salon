<?php
/**
 * Halaman login tunggal untuk customer & admin.
 */
$pageTitle = 'Login Akun';
require_once __DIR__ . '/../includes/header.php';
$error = flash('error');
$success = flash('success');
?>
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row g-0 auth-card overflow-hidden">
                    <div class="col-lg-6 p-5">
                        <h2 class="mb-3">Masuk Ke Ruang Reservasi</h2>
                        <p class="text-muted">Gunakan nomor HP (customer) atau username admin untuk menikmati dashboard personal.</p>
                        <?php if ($error): ?><div class="alert alert-danger"><?= $error; ?></div><?php endif; ?>
                        <?php if ($success): ?><div class="alert alert-success"><?= $success; ?></div><?php endif; ?>
                        <form method="post" action="/customer/process/login_process.php">
                            <div class="mb-3">
                                <label class="form-label">Nomor HP / Username</label>
                                <input type="text" class="form-control" name="identifier" placeholder="Masukkan No. HP atau Username" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Masukkan Password" required>
                            </div>
                            <button type="submit" class="btn btn-rose w-100">Masuk</button>
                        </form>
                        <p class="mt-3">Belum punya akun? <a href="/customer/register.php">Daftar sekarang</a></p>
                    </div>
                    <div class="col-lg-6 d-none d-lg-block auth-illustration" style="background-image:url('https://i.pinimg.com/736x/bf/1f/a7/bf1fa7dc7664cdb87ff151753c8f3ca1.jpg');">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
