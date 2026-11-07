<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'nip' => null,
            'nisn' => null,
            'role' => 'admin',
            'is_active' => true,
            'password' => Hash::make('admin123'),
        ]);
    }
}