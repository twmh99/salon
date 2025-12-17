@extends('layouts.app')

@section('content')
@php($title = 'Verifikasi Reservasi')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-4 col-xl-3">
            @include('admin.partials.taskbar', ['activeTask' => $activeTask])
        </div>
        <div class="col-lg-8 col-xl-9">
            <div class="dashboard-hero mb-4">
                <h1 class="h4 mb-0">Verifikasi Reservasi</h1>
                <p class="text-muted mb-0">Setujui atau tolak permintaan pelanggan dengan sekali klik.</p>
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
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Treatment</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Status Saat Ini</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendingReservations as $reservation)
                                @php($treatment = \App\Support\TreatmentCatalogue::find($reservation->schedule->jenis_treatment ?? '')['name'] ?? $reservation->schedule->jenis_treatment)
                                <tr>
                                    <td>#{{ $reservation->id }}</td>
                                    <td>{{ $reservation->customer->nama ?? '-' }}</td>
                                    <td>{{ $treatment }}</td>
                                    <td>{{ optional($reservation->schedule?->tanggal)->format('d M Y') ?? $reservation->schedule?->tanggal }}</td>
                                    <td>{{ $reservation->schedule?->waktu }}</td>
                                    <td><span class="badge-status {{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span></td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.verify.update', $reservation) }}" class="d-flex gap-2">
                                            @csrf
                                            @method('PUT')
                                            <button name="status" value="confirmed" class="btn btn-success btn-sm" {{ $reservation->status === 'confirmed' ? 'disabled' : '' }}>Approve</button>
                                            <button name="status" value="rejected" class="btn btn-danger btn-sm">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">Tidak ada reservasi untuk diverifikasi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
