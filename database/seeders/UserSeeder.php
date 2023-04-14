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
        User::factory()->create()->assignRole('admin');
        User::factory()->create()->assignRole('client');

        /**
         * [
'email => env('ADMIN_EMAIL, 'correo por defecto'),
password' => env('ADMIN_PASSWORD', 'contraseña por defecto'),
]
         */
    }
}
