<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Schedule;
use App\Support\TreatmentCatalogue;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'view');
        if (!in_array($tab, ['view', 'add'], true)) {
            $tab = 'view';
        }

        $schedules = collect();
        $reservations = collect();
        $editReservation = null;

        if ($tab === 'view') {
            $schedules = Schedule::query()
                ->withCount(['reservations as reserved_count' => function ($query): void {
                    $query->whereIn('status', ['pending', 'confirmed']);
                }])
                ->orderBy('tanggal')
                ->orderBy('waktu')
                ->limit(50)
                ->get();
        } else {
            $reservations = Reservation::query()
                ->with(['customer', 'schedule'])
                ->orderByDesc('id')
                ->get();

            $reservationId = $request->integer('reservation_id');
            if ($reservationId) {
                $editReservation = $reservations->firstWhere('id', $reservationId);
                if (!$editReservation) {
                    session()->flash('error', 'Data reservasi tidak ditemukan.');
                }
            }
        }

        return view('admin.schedules', [
            'tab' => $tab,
            'schedules' => $schedules,
            'reservations' => $reservations,
            'editReservation' => $editReservation,
            'treatments' => TreatmentCatalogue::all(),
            'activeTask' => $tab === 'add' ? 'add_schedule' : 'view_schedule',
        ]);
    }

    public function updateSchedule(Request $request, Schedule $schedule): RedirectResponse
    {
        $data = $request->validate([
            'jenis_treatment' => ['required', Rule::in(array_column(TreatmentCatalogue::all(), 'id'))],
            'tanggal' => ['required', 'date'],
            'waktu' => ['required'],
            'slot' => ['required', 'integer', 'min:1'],
        ]);

        $schedule->update([
            'jenis_treatment' => $data['jenis_treatment'],
            'tanggal' => $data['tanggal'],
            'waktu' => Carbon::parse($data['waktu'])->format('H:i'),
            'slot' => $data['slot'],
        ]);

        return redirect()->route('admin.schedules', ['tab' => 'view'])
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function updateCapacity(Request $request, Schedule $schedule): RedirectResponse
    {
        $data = $request->validate([
            'slot' => ['required', 'integer', 'min:1'],
        ]);

        $schedule->update(['slot' => $data['slot']]);

        return redirect()->route('admin.schedules', ['tab' => 'view'])
            ->with('success', 'Jumlah karyawan diperbarui.');
    }

    public function deleteSchedule(Schedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return redirect()->route('admin.schedules', ['tab' => 'view'])
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    public function updateReservation(Request $request, Reservation $reservation): RedirectResponse
    {
        $treatments = array_column(TreatmentCatalogue::all(), 'id');
        $data = $request->validate([
            'jenis_treatment' => ['required', Rule::in($treatments)],
            'tanggal' => ['required', 'date'],
            'waktu' => ['required'],
        ]);

        $time = Carbon::parse($data['waktu'])->format('H:i');

        $schedule = Schedule::query()->firstOrCreate(
            [
                'jenis_treatment' => $data['jenis_treatment'],
                'tanggal' => $data['tanggal'],
                'waktu' => $time,
            ],
            ['slot' => 3]
        );

        $booked = Reservation::query()
            ->where('schedule_id', $schedule->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('id', '!=', $reservation->id)
            ->count();

        if ($booked >= $schedule->slot) {
            return redirect()->route('admin.schedules', ['tab' => 'add', 'reservation_id' => $reservation->id])
                ->with('error', 'Slot jadwal penuh, pilih jadwal lain.');
        }

        $reservation->update([
            'schedule_id' => $schedule->id,
        ]);

        return redirect()->route('admin.schedules', ['tab' => 'add'])
            ->with('success', 'Reservasi #' . $reservation->id . ' berhasil diperbarui.');
    }

    public function deleteReservation(Reservation $reservation): RedirectResponse
    {
        $reservation->delete();

        return redirect()->route('admin.schedules', ['tab' => 'add'])
            ->with('success', 'Reservasi #' . $reservation->id . ' berhasil dihapus.');
    }
}
