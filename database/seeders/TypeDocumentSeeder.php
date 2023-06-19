<?php

namespace Database\Seeders;

use App\Domain\User\Models\TypeDocument;
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
            'code' => 'CE',
            'name' => 'Cédula de extranjería'
        ]);

        TypeDocument::create([
            'code' => 'TI',
            'name' => 'Tarjeta de identidad'
        ]);

        TypeDocument::create([
            'code' => 'NIT',
            'name' => 'Número de de Identificación Tributaria'
        ]);

        TypeDocument::create([
            'code' => 'RUT',
            'name' => 'Registro único tributario'
        ]);

        TypeDocument::create([
            'code' => 'PPN',
            'name' => 'Pasaporte'
        ]);

        TypeDocument::create([
            'code' => 'TAX',
            'name' => 'TAX'
        ]);

        TypeDocument::create([
            'code' => 'LIC',
            'name' => 'Labeler Identification Code'
        ]);
    }
}
