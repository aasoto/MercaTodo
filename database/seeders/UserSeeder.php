<?php

namespace Database\Seeders;

use App\Domain\User\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::newFactory()->create([
            'email' => env('ADMIN_EMAIL', 'admin@example.com'),
            'password' => Hash::make(env('ADMIN_PASSWORD', '12345678')),
        ])->assignRole('admin');

        User::newFactory()->create([
            'email' => env('CLIENT_EMAIL', 'client@example.com'),
            'password' => Hash::make(env('CLIENT_PASSWORD', '12345678')),
        ])->assignRole('client');
    }
}
