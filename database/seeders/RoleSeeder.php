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

        $permission_1 = Permission::create(['name' => 'show-dashboard']);

        // Permissions users
        $permission_2 = Permission::create(['name' => 'create-users']);
        $permission_3 = Permission::create(['name' => 'consult-users']);
        $permission_4 = Permission::create(['name' => 'update-users']);
        $permission_5 = Permission::create(['name' => 'disable-users']);

        // Permissions products
        $permission_6 = Permission::create(['name' => 'create-products']);
        $permission_7 = Permission::create(['name' => 'consult-products']);
        $permission_8 = Permission::create(['name' => 'show-products']);
        $permission_9 = Permission::create(['name' => 'update-products']);
        $permission_10 = Permission::create(['name' => 'disable-products']);
        $permission_11 = Permission::create(['name' => 'delete-products']);

        $permission_12 = Permission::create(['name' => 'import-excel-data']); // El administrador podrá importar al sistema de manera masiva una lista de productos en excel.
        $permission_13 = Permission::create(['name' => 'export-excel-data']); // El administrador podrá descargar una lista en excel de los productos registrados para realizar su modificación y subirlos nuevamente al sistema de manera masiva.
        $permission_14 = Permission::create(['name' => 'generate-reports']); // El administrador podrá generar reportes del sistema con información relevante para la gestión de su negocio.

        $permission_15 = Permission::create(['name' => 'client-history-orders']);
        $permission_16 = Permission::create(['name' => 'client-detail-order']);
        $permission_17 = Permission::create(['name' => 'client-create-order']);
        $permission_18 = Permission::create(['name' => 'client-payment-process']);


        $role_1->syncPermissions([
            $permission_1,
            $permission_2,
            $permission_3,
            $permission_4,
            $permission_5,
            $permission_6,
            $permission_7,
            $permission_8,
            $permission_9,
            $permission_10,
            $permission_11,
            $permission_12,
            $permission_13,
            $permission_14,
        ]);

        $role_2->syncPermissions([
            $permission_15,
            $permission_16,
            $permission_17,
            $permission_18,
        ]);
    }
}
