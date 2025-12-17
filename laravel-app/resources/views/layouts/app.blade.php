@php
    $pageTitle = $title ?? ($pageTitle ?? config('app.name'));
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }} | {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body class="bg-soft">
<nav class="navbar navbar-expand-lg navbar-light bg-translucent shadow-sm sticky-top glass-nav">
    <div class="container">
        <a class="navbar-brand fw-bold text-uppercase tracking-wide" href="{{ route('landing') }}">DBeauty Spa</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 text-uppercase small">
                <li class="nav-item"><a class="nav-link" href="{{ route('landing') }}#hero">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('landing') }}#treatments">Treatment</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('landing') }}#steps">Langkah Booking</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('landing') }}#contact">Kontak</a></li>
            </ul>
            <div class="d-flex gap-2">
                @if ($currentCustomer)
                    <div class="dropdown">
                        <button class="btn btn-outline-rose dropdown-toggle" data-bs-toggle="dropdown">
                            Halo, {{ $currentCustomer->nama }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}">Dashboard Customer</a></li>
                            <li><a class="dropdown-item" href="{{ route('customer.booking') }}">Buat Reservasi</a></li>
                            <li><a class="dropdown-item" href="{{ route('customer.reservations') }}">Riwayat Reservasi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('customer.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @elseif ($currentAdmin)
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            Admin: {{ $currentAdmin->username }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.schedules', ['tab' => 'view']) }}">Kelola Jadwal</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.history') }}">Histori Reservasi</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.verify') }}">Verifikasi Reservasi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a class="btn btn-outline-rose" href="{{ route('customer.login') }}">Login</a>
                    <a class="btn btn-rose" href="{{ route('customer.register') }}">Daftar</a>
                @endif
            </div>
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer class="py-5" id="site-footer">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-4">
                <h5 class="mb-2">DBeauty Skincare & Day Spa</h5>
                <p class="mb-0">
                    Jl. Cempaka No.27A, Ngepos, Klaten, Kec. Klaten Tengah, Kabupaten Klaten, Jawa Tengah 57411<br>
                    Telp: 021-123456 | WhatsApp: 0812-8888-9999
                </p>
            </div>
            <div class="col-lg-4">
                <p class="mb-1"><strong>Jam Operasional</strong></p>
                <p class="mb-0">Senin - Jumat: 09.00 - 21.00</p>
                <p class="mb-0">Sabtu - Minggu: 08.00 - 22.00</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <p class="mb-1">Ikuti kami:</p>
                <a href="https://instagram.com" target="_blank" rel="noopener" class="me-3">Instagram</a>
                <a href="https://tiktok.com" target="_blank" rel="noopener" class="me-3">TikTok</a>
                <a href="https://wa.me/6281288889999" target="_blank" rel="noopener">WhatsApp</a>
                <p class="small mb-0 mt-2">&copy; {{ date('Y') }} DBeauty Spa. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
