@extends('layouts.app')

@section('content')
@php($title = 'Histori Reservasi')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-4 col-xl-3">
            @include('admin.partials.taskbar', ['activeTask' => $activeTask])
        </div>
        <div class="col-lg-8 col-xl-9">
            <div class="dashboard-hero mb-4">
                <h1 class="h4 mb-0">Histori Reservasi</h1>
                <p class="text-muted mb-0">Seluruh perubahan status reservasi ditampilkan berdasarkan waktu terbaru.</p>
            </div>
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
                                <th>Status</th>
                                <th>Terakhir Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($history as $reservation)
                                @php($treatment = \App\Support\TreatmentCatalogue::find($reservation->schedule->jenis_treatment ?? '')['name'] ?? $reservation->schedule->jenis_treatment)
                                <tr>
                                    <td>#{{ $reservation->id }}</td>
                                    <td>{{ $reservation->customer->nama ?? '-' }}</td>
                                    <td>{{ $treatment }}</td>
                                    <td>{{ optional($reservation->schedule?->tanggal)->format('d M Y') ?? $reservation->schedule?->tanggal }}</td>
                                    <td>{{ $reservation->schedule?->waktu }}</td>
                                    <td><span class="badge-status {{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span></td>
                                    <td>{{ $reservation->updated_at?->timezone(config('app.timezone'))->format('d M Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">Belum ada data histori.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
