<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin if not exists
        if (!User::where('email', 'admin@library.com')->exists()) {
            User::create([
                'name' => 'Admin Perpustakaan',
                'email' => 'admin@library.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);
        }
    }
}

