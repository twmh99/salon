<?php
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? APP_NAME;
$authCustomer = authCustomer();
$authAdmin = authAdmin();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle); ?> | <?= APP_NAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg-soft">
<nav class="navbar navbar-expand-lg navbar-light bg-translucent shadow-sm sticky-top glass-nav">
    <div class="container">
        <a class="navbar-brand fw-bold text-uppercase tracking-wide" href="/">DBeauty Spa</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 text-uppercase small">
                <li class="nav-item"><a class="nav-link" href="/index.php#hero">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/index.php#treatments">Treatment</a></li>
                <li class="nav-item"><a class="nav-link" href="/index.php#steps">Langkah Booking</a></li>
                <li class="nav-item"><a class="nav-link" href="/index.php#contact">Kontak</a></li>
            </ul>
            <div class="d-flex gap-2">
                <?php if ($authCustomer): ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-rose dropdown-toggle" data-bs-toggle="dropdown">
                            Halo, <?= htmlspecialchars($authCustomer['nama']); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/customer/dashboard.php">Dashboard Customer</a></li>
                            <li><a class="dropdown-item" href="/customer/booking.php">Buat Reservasi</a></li>
                            <li><a class="dropdown-item" href="/customer/my-reservations.php">Riwayat Reservasi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/customer/process/logout.php">Logout</a></li>
                        </ul>
                    </div>
                <?php elseif ($authAdmin): ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            Admin: <?= htmlspecialchars($authAdmin['username']); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/admin/dashboard.php">Dashboard Admin</a></li>
                            <li><a class="dropdown-item" href="/admin/schedules.php?tab=view">Kelola Jadwal</a></li>
                            <li><a class="dropdown-item" href="/admin/history.php">Histori Reservasi</a></li>
                            <li><a class="dropdown-item" href="/admin/verify.php">Verifikasi Reservasi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/admin/process/logout.php">Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a class="btn btn-outline-rose" href="/customer/login.php">Login</a>
                    <a class="btn btn-rose" href="/customer/register.php">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
