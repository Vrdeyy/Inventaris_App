<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin (jika belum ada)
        if (!User::where('email', 'admin@admin.com')->exists()) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]);
        }

        // User 1 (jika belum ada)
        if (!User::where('email', 'user1@user.com')->exists()) {
            User::create([
                'name' => 'Petugas Inventaris 1',
                'email' => 'user1@user.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ]);
        }

        // User 2 (jika belum ada)
        if (!User::where('email', 'user2@user.com')->exists()) {
            User::create([
                'name' => 'Petugas Inventaris 2',
                'email' => 'user2@user.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ]);
        }
    }
}
