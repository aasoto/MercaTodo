<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Product::factory()->count(5)->create();
        // $count = 1;
        // for ($i=0; $i < 105; $i++) {
        //     Product::factory()->create([
        //         'picture_1' => 'Imagen'.$count++.'.jpg',
        //         'picture_2' => 'Imagen'.$count++.'.jpg',
        //         'picture_3' => 'Imagen'.$count++.'.jpg',
        //     ]);
        // }
    }
}
