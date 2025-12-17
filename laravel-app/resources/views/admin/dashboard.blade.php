@extends('layouts.app')

@section('content')
@php($title = 'Dashboard Admin')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-4 col-xl-3">
            @include('admin.partials.taskbar')
        </div>
        <div class="col-lg-8 col-xl-9">
            <div class="dashboard-hero mb-5">
                <h1 class="h3">Dashboard Admin</h1>
                <p class="mb-0 text-muted">Monitor performa reservasi, jadwal, dan approval dalam satu tampilan elegan.</p>
            </div>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="row g-3 mb-5">
                <div class="col-md-3">
                    <div class="stat-card text-center p-4">
                        <p class="text-muted">Customer</p>
                        <h3>{{ $stats['customers'] }}</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card text-center p-4">
                        <p class="text-muted">Pending</p>
                        <h3>{{ $stats['pending'] }}</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card text-center p-4">
                        <p class="text-muted">Confirmed</p>
                        <h3>{{ $stats['confirmed'] }}</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card text-center p-4">
                        <p class="text-muted">Jadwal</p>
                        <h3>{{ $stats['schedules'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="table-modern p-4">
                <h3 class="h5 mb-3">Reservasi Terbaru</h3>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Treatment</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentReservations as $reservation)
                                @php($treatment = \App\Support\TreatmentCatalogue::find($reservation->schedule->jenis_treatment ?? '')['name'] ?? $reservation->schedule->jenis_treatment)
                                <tr>
                                    <td>#{{ $reservation->id }}</td>
                                    <td>{{ $reservation->customer->nama ?? '-' }}</td>
                                    <td>{{ $treatment }}</td>
                                    <td>{{ optional($reservation->schedule?->tanggal)->format('d M Y') ?? $reservation->schedule?->tanggal }}</td>
                                    <td>{{ $reservation->schedule?->waktu }}</td>
                                    <td><span class="badge-status {{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">Belum ada reservasi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
