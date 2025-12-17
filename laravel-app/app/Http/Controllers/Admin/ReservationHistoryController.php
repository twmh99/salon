<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\View\View;

class ReservationHistoryController extends Controller
{
    public function __invoke(): View
    {
        $history = Reservation::query()
            ->with(['customer', 'schedule'])
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get();

        return view('admin.history', [
            'history' => $history,
            'activeTask' => 'history_reservation',
        ]);
    }
}
