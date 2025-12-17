<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Verification;
use App\Services\AuthSessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function index(): View
    {
        $pendingReservations = Reservation::query()
            ->with(['customer', 'schedule'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('id')
            ->get();

        return view('admin.verify', [
            'pendingReservations' => $pendingReservations,
            'activeTask' => '',
        ]);
    }

    public function update(Request $request, Reservation $reservation, AuthSessionService $authSession): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['confirmed', 'rejected'])],
        ]);

        $admin = $authSession->admin();
        abort_if(!$admin, 403);

        $reservationStatus = $data['status'] === 'confirmed' ? 'confirmed' : 'cancelled';
        $verificationStatus = $data['status'] === 'confirmed' ? 'approved' : 'rejected';

        DB::transaction(function () use ($reservation, $reservationStatus, $admin, $verificationStatus): void {
            $reservation->update(['status' => $reservationStatus]);

            Verification::query()->create([
                'reservation_id' => $reservation->id,
                'admin_id' => $admin->id,
                'status' => $verificationStatus,
                'tanggal_verifikasi' => now(),
            ]);
        });

        return redirect()->route('admin.verify')
            ->with('success', 'Reservasi #' . $reservation->id . ' diperbarui menjadi ' . $reservationStatus . '.');
    }
}
