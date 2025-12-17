<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\AuthSessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private readonly AuthSessionService $authSession)
    {
    }

    public function showLogin(): View
    {
        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $admin = Admin::query()->where('username', $data['username'])->first();
        if (!$admin || !Hash::check($data['password'], $admin->password)) {
            return back()->withInput(['username' => $data['username']])
                ->with('error', 'Username atau password salah.');
        }

        $this->authSession->logoutCustomer();
        $this->authSession->loginAdmin($admin);

        return redirect()->route('admin.dashboard')->with('success', 'Selamat datang, ' . $admin->username . '.');
    }

    public function logout(): RedirectResponse
    {
        $this->authSession->logoutAdmin();

        return redirect()->route('admin.login')->with('success', 'Anda telah logout.');
    }
}
