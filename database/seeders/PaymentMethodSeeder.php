<?php

namespace Database\Seeders;

use App\Domain\Order\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::create([
            'code' => 'NORMAL',
            'name' => 'Pago normal',
        ]);

        PaymentMethod::create([
            'code' => 'ALLOW_PARTIAL',
            'name' => 'Pago mixto',
        ]);

        PaymentMethod::create([
            'code' => 'RECURRING',
            'name' => 'Pago pago recurrente',
        ]);
    }
}
