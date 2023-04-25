<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //['unit', 'pair', 'dozen', 'box']
        Unit::create([
            'code' => 'unit',
            'name' => 'Unidad'
        ]);

        Unit::create([
            'code' => 'pair',
            'name' => 'Par'
        ]);

        Unit::create([
            'code' => 'dozen',
            'name' => 'Docena'
        ]);

        Unit::create([
            'code' => 'box',
            'name' => 'Caja'
        ]);
    }
}
