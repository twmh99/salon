@extends('layouts.app')

@section('content')
@php($title = 'Dashboard Customer')
<div class="container py-5">
    <div class="dashboard-hero mb-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <p class="hero-badge mb-1 text-uppercase">Selamat Datang</p>
                <h1 class="h3 mb-2">{{ $customer->nama }}</h1>
                <p class="mb-0">Atur jadwal treatment, pantau status reservasi, dan nikmati promo khusus member.</p>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-rose" href="{{ route('customer.booking') }}">Buat Reservasi</a>
                <a class="btn btn-outline-rose" href="{{ route('customer.reservations') }}">Riwayat</a>
            </div>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="row g-3 mb-5">
        <div class="col-md-4">
            <div class="stat-card text-center p-4">
                <p class="text-muted mb-1">Total Reservasi</p>
                <h3>{{ $totalCount }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card text-center p-4">
                <p class="text-muted mb-1">Menunggu Verifikasi</p>
                <h3>{{ $pendingCount }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card text-center p-4">
                <p class="text-muted mb-1">Sudah Dikonfirmasi</p>
                <h3>{{ $confirmedCount }}</h3>
            </div>
        </div>
    </div>
    <div class="table-modern p-4">
        <h3 class="h5 mb-3">Reservasi Terbaru</h3>
        @if ($reservations->isEmpty())
            <p class="text-muted mb-0">Belum ada reservasi. Klik "Buat Reservasi" untuk mulai.</p>
        @else
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Treatment</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservations as $reservation)
                            @php($treatment = \App\Support\TreatmentCatalogue::find($reservation->schedule->jenis_treatment ?? '')['name'] ?? $reservation->schedule->jenis_treatment)
                            <tr>
                                <td>{{ $treatment }}</td>
                                <td>{{ optional($reservation->schedule?->tanggal)->format('d M Y') ?? $reservation->schedule?->tanggal }}</td>
                                <td>{{ $reservation->schedule?->waktu }}</td>
                                <td><span class="badge-status {{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span></td>
                                <td>{{ $reservation->reservation_date?->timezone(config('app.timezone'))->format('d M Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
