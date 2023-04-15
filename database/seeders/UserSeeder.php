<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->create([
                'email' => env('ADMIN_EMAIL', 'andresalfredosotosuarez@gmail.com'),
                'password' => env('ADMIN_PASSWORD', '12345678'),
            ])
        ->assignRole('admin');

        User::factory()->create()->assignRole('client');
    }
}
