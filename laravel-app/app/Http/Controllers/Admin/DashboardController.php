<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Schedule;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'customers' => Customer::query()->whereNull('deleted_at')->count(),
            'pending' => Reservation::query()->where('status', 'pending')->count(),
            'confirmed' => Reservation::query()->where('status', 'confirmed')->count(),
            'schedules' => Schedule::query()->count(),
        ];

        $recentReservations = Reservation::query()
            ->with(['customer', 'schedule'])
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentReservations' => $recentReservations,
            'activeTask' => '',
        ]);
    }
}
