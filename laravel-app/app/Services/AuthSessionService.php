<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Customer;

class AuthSessionService
{
    public function customer(): ?Customer
    {
        $id = session('customer_id');
        return $id ? Customer::query()->find($id) : null;
    }

    public function admin(): ?Admin
    {
        $id = session('admin_id');
        return $id ? Admin::query()->find($id) : null;
    }

    public function loginCustomer(Customer $customer): void
    {
        session(['customer_id' => $customer->id]);
    }

    public function loginAdmin(Admin $admin): void
    {
        session(['admin_id' => $admin->id]);
    }

    public function logoutCustomer(): void
    {
        session()->forget('customer_id');
    }

    public function logoutAdmin(): void
    {
        session()->forget('admin_id');
    }
}
