<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Customer;
use App\Services\AuthSessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private readonly AuthSessionService $authSession)
    {
    }

    public function showLogin(): View
    {
        return view('customer.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $identifier = $credentials['identifier'];
        $password = $credentials['password'];

        $customer = Customer::query()
            ->where('nomor_hp', $identifier)
            ->whereNull('deleted_at')
            ->first();

        if ($customer && Hash::check($password, $customer->password)) {
            $this->authSession->logoutAdmin();
            $this->authSession->loginCustomer($customer);

            return redirect()->route('customer.dashboard')
                ->with('success', 'Selamat datang kembali, ' . $customer->nama . '!');
        }

        $admin = Admin::query()->where('username', $identifier)->first();
        if ($admin && Hash::check($password, $admin->password)) {
            $this->authSession->logoutCustomer();
            $this->authSession->loginAdmin($admin);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Berhasil login sebagai admin.');
        }

        return back()->withInput(['identifier' => $identifier])
            ->with('error', 'Kredensial tidak valid.');
    }

    public function showRegister(): View
    {
        return view('customer.auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nomor_hp' => ['required', 'string', 'max:20', Rule::unique('customers', 'nomor_hp')],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $customer = Customer::query()->create([
            'nama' => $data['nama'],
            'nomor_hp' => $data['nomor_hp'],
            'password' => Hash::make($data['password']),
        ]);

        $this->authSession->logoutAdmin();
        $this->authSession->loginCustomer($customer);

        return redirect()->route('customer.dashboard')->with('success', 'Registrasi berhasil. Selamat datang, ' . $customer->nama . '!');
    }

    public function logout(): RedirectResponse
    {
        $this->authSession->logoutCustomer();

        return redirect()->route('customer.login')->with('success', 'Anda telah logout.');
    }
}
