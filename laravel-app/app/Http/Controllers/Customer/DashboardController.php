<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Services\AuthSessionService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(AuthSessionService $authSession): View
    {
        $customer = $authSession->customer();

        abort_if(!$customer, 403);

        $totalCount = Reservation::query()->whereBelongsTo($customer)->count();
        $pendingCount = Reservation::query()
            ->whereBelongsTo($customer)
            ->where('status', 'pending')
            ->count();
        $confirmedCount = Reservation::query()
            ->whereBelongsTo($customer)
            ->where('status', 'confirmed')
            ->count();

        $reservations = Reservation::query()
            ->with('schedule')
            ->whereBelongsTo($customer)
            ->orderByDesc('reservation_date')
            ->limit(5)
            ->get();

        return view('customer.dashboard', [
            'customer' => $customer,
            'totalCount' => $totalCount,
            'pendingCount' => $pendingCount,
            'confirmedCount' => $confirmedCount,
            'reservations' => $reservations,
        ]);
    }
}
