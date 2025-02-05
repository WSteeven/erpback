<?php

namespace Database\Seeders\ComprasProveedores;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Config\Permisos;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\ComprasProveedores\PermisosSeeder"
     * @return void
     */
    public function run()
    {
        $contabilidad = Role::firstOrCreate(['name' => User::ROL_CONTABILIDAD]);
        $gerente = Role::firstOrCreate(['name' => User::ROL_GERENTE]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'generador_cash'])->syncRoles([$gerente, $contabilidad]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'generador_cash'])->syncRoles([$gerente, $contabilidad]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'generador_cash'])->syncRoles([$gerente, $contabilidad]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'generador_cash'])->syncRoles([$gerente, $contabilidad]);
    }
}
