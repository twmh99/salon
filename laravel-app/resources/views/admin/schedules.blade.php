@extends('layouts.app')

@section('content')
@php($title = 'Kelola Jadwal')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-4 col-xl-3">
            @include('admin.partials.taskbar', ['activeTask' => $activeTask])
        </div>
        <div class="col-lg-8 col-xl-9">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($tab === 'add')
                <div class="row g-4">
                    @if ($editReservation)
                        <div class="col-lg-5 col-xl-4">
                            <div class="treatment-card h-100 manage-panel" id="schedule-form">
                                <h2 class="h5 mb-3">Edit Jadwal Reservasi Customer</h2>
                                <form method="POST" action="{{ route('admin.reservations.update', $editReservation) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label">Customer</label>
                                        <input type="text" class="form-control" value="{{ $editReservation->customer->nama }} ({{ $editReservation->customer->nomor_hp }})" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jenis Treatment</label>
                                        <select name="jenis_treatment" class="form-select">
                                            @foreach ($treatments as $t)
                                                <option value="{{ $t['id'] }}" {{ $editReservation->schedule?->jenis_treatment === $t['id'] ? 'selected' : '' }}>
                                                    {{ $t['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal</label>
                                        <input type="date" name="tanggal" class="form-control" value="{{ $editReservation->schedule?->tanggal?->toDateString() ?? $editReservation->schedule?->tanggal }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jam</label>
                                        <input type="time" name="waktu" class="form-control" value="{{ $editReservation->schedule?->waktu }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-rose w-100 mb-2">Simpan Jadwal Customer</button>
                                    <a href="{{ route('admin.schedules', ['tab' => 'add']) }}" class="btn btn-outline-rose w-100">Batalkan Edit</a>
                                </form>
                            </div>
                        </div>
                    @endif
                    <div class="{{ $editReservation ? 'col-lg-7 col-xl-8' : 'col-12' }}">
                        <div class="table-modern p-4" id="schedule-list">
                            <h2 class="h5 mb-3">Daftar Reservasi</h2>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Nomor HP</th>
                                            <th>Treatment</th>
                                            <th>Tanggal</th>
                                            <th>Jam</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($reservations as $reservation)
                                            @php($treatment = \App\Support\TreatmentCatalogue::find($reservation->schedule->jenis_treatment ?? '')['name'] ?? $reservation->schedule->jenis_treatment)
                                            <tr>
                                                <td>{{ $reservation->customer->nama }}</td>
                                                <td>{{ $reservation->customer->nomor_hp }}</td>
                                                <td>{{ $treatment }}</td>
                                                <td>{{ optional($reservation->schedule?->tanggal)->format('d M Y') ?? $reservation->schedule?->tanggal }}</td>
                                                <td>{{ $reservation->schedule?->waktu }}</td>
                                                <td><span class="badge-status {{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span></td>
                                                <td>
                                                    <div class="table-actions justify-content-center">
                                                        <a href="{{ route('admin.schedules', ['tab' => 'add', 'reservation_id' => $reservation->id]) }}" class="btn btn-action btn-action-edit">Edit</a>
                                                        <form method="POST" action="{{ route('admin.reservations.destroy', $reservation) }}" onsubmit="return confirm('Hapus reservasi #{{ $reservation->id }}?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-action btn-action-delete">Hapus</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="7" class="text-center text-muted">Belum ada reservasi.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="table-modern p-4" id="schedule-list">
                    <h2 class="h5 mb-3">Daftar Jadwal</h2>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Treatment</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Terisi</th>
                                    <th>Karyawan (Slot)</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($schedules as $schedule)
                                    @php($treatment = \App\Support\TreatmentCatalogue::find($schedule->jenis_treatment)['name'] ?? $schedule->jenis_treatment)
                                    <tr>
                                        <td>{{ $treatment }}</td>
                                        <td>{{ $schedule->tanggal->format('d M Y') }}</td>
                                        <td><span class="time-badge {{ ($schedule->reserved_count ?? 0) >= $schedule->slot ? 'slot-full' : '' }}">{{ $schedule->waktu }}</span></td>
                                        <td>{{ $schedule->reserved_count ?? 0 }}/{{ $schedule->slot }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('admin.schedules.capacity', $schedule) }}" class="d-flex gap-2 align-items-center">
                                                @csrf
                                                @method('PUT')
                                                <input type="number" name="slot" value="{{ $schedule->slot }}" min="1" class="form-control form-control-sm" style="max-width:90px;">
                                                <button class="btn btn-action btn-action-edit" type="submit">Simpan</button>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="table-actions justify-content-center">
                                                <button class="btn btn-action btn-action-edit btn-edit-schedule"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editScheduleModal"
                                                        data-id="{{ $schedule->id }}"
                                                        data-treatment="{{ $schedule->jenis_treatment }}"
                                                        data-date="{{ $schedule->tanggal->toDateString() }}"
                                                        data-time="{{ $schedule->waktu }}"
                                                        data-slot="{{ $schedule->slot }}">
                                                    Edit
                                                </button>
                                                <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" onsubmit="return confirm('Hapus jadwal ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-action btn-action-delete" type="submit">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted">Belum ada jadwal.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="editScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="" class="modal-content" id="editScheduleForm">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Jadwal Treatment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Treatment</label>
                    <select name="jenis_treatment" id="editScheduleTreatment" class="form-select" required>
                        @foreach ($treatments as $t)
                            <option value="{{ $t['id'] }}">{{ $t['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" id="editScheduleDate" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jam</label>
                    <input type="time" name="waktu" id="editScheduleTime" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Slot</label>
                    <input type="number" name="slot" id="editScheduleSlot" class="form-control" min="1" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-rose" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-rose">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.btn-edit-schedule').forEach(btn => {
    btn.addEventListener('click', () => {
        const form = document.getElementById('editScheduleForm');
        form.action = "{{ route('admin.schedules.update', ['schedule' => '__ID__']) }}".replace('__ID__', btn.dataset.id);

        document.getElementById('editScheduleTreatment').value = btn.dataset.treatment;
        document.getElementById('editScheduleDate').value = btn.dataset.date;
        document.getElementById('editScheduleTime').value = btn.dataset.time.substring(0,5);
        document.getElementById('editScheduleSlot').value = btn.dataset.slot;
    });
});
</script>
@endsection
