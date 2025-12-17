<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Schedule;
use App\Services\AuthSessionService;
use App\Support\TreatmentCatalogue;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function create(AuthSessionService $authSession): View
    {
        $customer = $authSession->customer();
        abort_if(!$customer, 403);

        $availableSchedules = Schedule::query()
            ->withCount(['reservations as reserved_count' => function ($query): void {
                $query->whereIn('status', ['pending', 'confirmed']);
            }])
            ->where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->orderBy('waktu')
            ->limit(30)
            ->get()
            ->map(function (Schedule $schedule) {
                $schedule->available_slots = max($schedule->slot - ($schedule->reserved_count ?? 0), 0);
                return $schedule;
            })
            ->filter(fn (Schedule $schedule) => $schedule->available_slots > 0)
            ->values();

        return view('customer.booking', [
            'treatments' => TreatmentCatalogue::all(),
            'availableSchedules' => $availableSchedules,
        ]);
    }

    public function store(Request $request, AuthSessionService $authSession): RedirectResponse
    {
        $customer = $authSession->customer();
        abort_if(!$customer, 403);

        $treatmentCodes = array_column(TreatmentCatalogue::all(), 'id');

        $data = $request->validate([
            'jenis_treatment' => ['required', Rule::in($treatmentCodes)],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'waktu' => ['required'],
        ]);

        $time = Carbon::parse($data['waktu'])->format('H:i');

        $schedule = Schedule::query()
            ->where('jenis_treatment', $data['jenis_treatment'])
            ->where('tanggal', $data['tanggal'])
            ->where('waktu', $time)
            ->first();

        if (!$schedule) {
            return back()->withInput()->with('error', 'Slot jadwal belum tersedia. Silakan hubungi admin.');
        }

        $booked = Reservation::query()
            ->where('schedule_id', $schedule->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        if ($booked >= $schedule->slot) {
            return back()->withInput()->with('error', 'Slot sudah penuh, pilih jadwal lain.');
        }

        Reservation::query()->create([
            'customer_id' => $customer->id,
            'schedule_id' => $schedule->id,
            'status' => 'pending',
            'reservation_date' => now(),
        ]);

        return redirect()->route('customer.reservations')
            ->with('success', 'Reservasi berhasil diajukan dan menunggu verifikasi admin.');
    }

    public function history(AuthSessionService $authSession): View
    {
        $customer = $authSession->customer();
        abort_if(!$customer, 403);

        $reservations = Reservation::query()
            ->with('schedule')
            ->whereBelongsTo($customer)
            ->orderByDesc('reservation_date')
            ->get();

        return view('customer.reservations', [
            'reservations' => $reservations,
        ]);
    }
}
