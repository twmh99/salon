<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ReservationHistoryController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\ReservationController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('landing');

Route::prefix('customer')->name('customer.')->group(function (): void {
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.submit');

    Route::middleware('customer.auth')->group(function (): void {
        Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', CustomerDashboardController::class)->name('dashboard');
        Route::get('/booking', [ReservationController::class, 'create'])->name('booking');
        Route::post('/booking', [ReservationController::class, 'store'])->name('booking.store');
        Route::get('/reservations', [ReservationController::class, 'history'])->name('reservations');
    });
});

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');

    Route::middleware('admin.auth')->group(function (): void {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');

        Route::get('/schedules', [AdminScheduleController::class, 'index'])->name('schedules');
        Route::put('/schedules/{schedule}', [AdminScheduleController::class, 'updateSchedule'])->name('schedules.update');
        Route::put('/schedules/{schedule}/capacity', [AdminScheduleController::class, 'updateCapacity'])->name('schedules.capacity');
        Route::delete('/schedules/{schedule}', [AdminScheduleController::class, 'deleteSchedule'])->name('schedules.destroy');

        Route::put('/reservations/{reservation}', [AdminScheduleController::class, 'updateReservation'])->name('reservations.update');
        Route::delete('/reservations/{reservation}', [AdminScheduleController::class, 'deleteReservation'])->name('reservations.destroy');

        Route::get('/history', ReservationHistoryController::class)->name('history');
        Route::get('/verify', [VerificationController::class, 'index'])->name('verify');
        Route::put('/verify/{reservation}', [VerificationController::class, 'update'])->name('verify.update');
    });
});
