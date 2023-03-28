<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_1 = Role::create(['name' => 'admin']);
        $role_2 = Role::create(['name' => 'client']);

        // Permissions clients
        $permission_1 = Permission::create(['name' => 'consult clients']);
        $permission_2 = Permission::create(['name' => 'update clients']);
        $permission_3 = Permission::create(['name' => 'disable clients']);

        // Permissions products
        $permission_4 = Permission::create(['name' => 'create products']);
        $permission_5 = Permission::create(['name' => 'consult products']);
        $permission_6 = Permission::create(['name' => 'update products']);
        $permission_7 = Permission::create(['name' => 'disable products']);
        $permission_8 = Permission::create(['name' => 'able products']);

        $permission_9 = Permission::create(['name' => 'consult current order']);
        $permission_10 = Permission::create(['name' => 'all orders']);
        $permission_11 = Permission::create(['name' => 'client history orders']);

        $permission_12 = Permission::create(['name' => 'import excel data']); // El administrador podrá importar al sistema de manera masiva una lista de productos en excel.
        $permission_13 = Permission::create(['name' => 'export excel data']); // El administrador podrá descargar una lista en excel de los productos registrados para realizar su modificación y subirlos nuevamente al sistema de manera masiva.
        $permission_14 = Permission::create(['name' => 'generate reports']); // El administrador podrá generar reportes del sistema con información relevante para la gestión de su negocio.

        $role_1->syncPermissions([
            $permission_1,
            $permission_2,
            $permission_3,
            $permission_4,
            $permission_5,
            $permission_6,
            $permission_7,
            $permission_10,
            $permission_12,
            $permission_13,
            $permission_14
        ]);

        $role_2->syncPermissions([
            $permission_5,
            $permission_8,
            $permission_9,
            $permission_11
        ]);
    }
}
