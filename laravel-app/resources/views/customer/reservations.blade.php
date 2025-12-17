@extends('layouts.app')

@section('content')
@php($title = 'Riwayat Reservasi')
<div class="container py-5">
    <div class="dashboard-hero mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <p class="hero-badge mb-1">Riwayat Reservasi</p>
            <h1 class="h4 mb-0">Pantau Semua Reservasi Kamu</h1>
            <p class="mb-0 text-muted">Status real-time dengan highlight warna agar mudah dibaca.</p>
        </div>
        <a href="{{ route('customer.booking') }}" class="btn btn-rose">Reservasi Baru</a>
    </div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <div class="table-modern p-4">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Treatment</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Status</th>
                        <th>Terakhir Update</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservations as $reservation)
                        @php($treatment = \App\Support\TreatmentCatalogue::find($reservation->schedule->jenis_treatment ?? '')['name'] ?? $reservation->schedule->jenis_treatment)
                        <tr>
                            <td>{{ $reservation->id }}</td>
                            <td>{{ $treatment }}</td>
                            <td>{{ optional($reservation->schedule?->tanggal)->format('d M Y') ?? $reservation->schedule?->tanggal }}</td>
                            <td>{{ $reservation->schedule?->waktu }}</td>
                            <td><span class="badge-status {{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span></td>
                            <td>{{ $reservation->updated_at?->timezone(config('app.timezone'))->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">Belum ada data reservasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
