<?php

namespace Database\Seeders;

use App\Domain\TypeDocument\Models\TypeDocument;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeDocument::create([
            'code' => 'CC',
            'name' => 'Cédula de ciudadanía'
        ]);

        TypeDocument::create([
            'code' => 'PAS',
            'name' => 'Pasaporte'
        ]);

        TypeDocument::create([
            'code' => 'CE',
            'name' => 'Cédula de Extranjería'
        ]);

        TypeDocument::create([
            'code' => 'O',
            'name' => 'Otro'
        ]);
    }
}
