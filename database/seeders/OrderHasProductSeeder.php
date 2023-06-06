<?php

namespace Database\Seeders;

use App\Domain\Order\Models\OrderHasProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderHasProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderHasProduct::factory(20)->create();
    }
}
