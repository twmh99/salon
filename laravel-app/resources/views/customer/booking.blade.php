@extends('layouts.app')

@section('content')
@php($title = 'Buat Reservasi')
<div class="container py-5">
    <div class="dashboard-hero mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <p class="hero-badge mb-1 text-uppercase">Reservasi Treatment</p>
                <h2 class="h4 mb-2">Atur Jadwal Me-Time</h2>
                <p class="mb-0">Pilih treatment yang kamu mau, kemudian kami akan mengunci slot terbaik untukmu.</p>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="treatment-card h-100">
                <h2 class="h4 mb-3">Form Reservasi</h2>
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('customer.booking.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Pilih Treatment</label>
                        <select class="form-select @error('jenis_treatment') is-invalid @enderror" name="jenis_treatment" required>
                            <option value="">-- pilih treatment --</option>
                            @foreach ($treatments as $item)
                                <option value="{{ $item['id'] }}" {{ old('jenis_treatment') === $item['id'] ? 'selected' : '' }}>
                                    {{ $item['name'] }} ({{ $item['price'] }})
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_treatment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" min="{{ now()->toDateString() }}" value="{{ old('tanggal') }}" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jam</label>
                        <input type="time" class="form-control @error('waktu') is-invalid @enderror" name="waktu" value="{{ old('waktu') }}" required>
                        @error('waktu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-rose w-100">Ajukan Reservasi</button>
                </form>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="treatment-card booking-reference">
                <h2 class="h5 mb-3">Slot Tersedia</h2>
                <p class="text-muted">Lihat jadwal untuk memastikan slot favoritmu masih tersedia.</p>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Treatment</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Sisa Slot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($availableSchedules as $slot)
                                @php($treatment = \App\Support\TreatmentCatalogue::find($slot->jenis_treatment)['name'] ?? $slot->jenis_treatment)
                                <tr>
                                    <td>{{ $treatment }}</td>
                                    <td>{{ $slot->tanggal->format('d M Y') }}</td>
                                    <td>{{ $slot->waktu }}</td>
                                    <td><span class="badge badge-treatment">{{ $slot->available_slots }} slot</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">Belum ada jadwal tersedia.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
