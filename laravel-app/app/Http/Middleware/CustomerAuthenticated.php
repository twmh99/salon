<?php

namespace App\Http\Middleware;

use App\Services\AuthSessionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerAuthenticated
{
    public function __construct(private readonly AuthSessionService $authSession)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->authSession->customer()) {
            return redirect()->route('customer.login')->with('error', 'Silakan login sebagai customer terlebih dahulu.');
        }

        return $next($request);
    }
}
