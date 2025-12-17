<?php

namespace App\Providers;

use App\Services\AuthSessionService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(AuthSessionService $authSession): void
    {
        View::composer('*', function ($view) use ($authSession): void {
            $view->with('currentCustomer', $authSession->customer());
            $view->with('currentAdmin', $authSession->admin());
        });
    }
}
