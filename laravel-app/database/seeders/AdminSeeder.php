<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed default admin account.
     */
    public function run(): void
    {
        Admin::query()->updateOrCreate(
            ['username' => 'admin'],
            ['password' => Hash::make('password')]
        );
    }
}
