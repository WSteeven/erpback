<?php

namespace Database\Seeders\Appenate;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Src\Config\Permisos;

class PermisosModuloAppenateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=Database\Seeders\Appenate\PermisosModuloAppenateSeeder
     *
     * @return void
     */
    public function run()
    {
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_appenate'])->syncRoles([User::ROL_ADMINISTRADOR]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'progresivas'])->syncRoles([User::ROL_ADMINISTRADOR]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'progresivas'])->syncRoles([User::ROL_ADMINISTRADOR]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'progresivas'])->syncRoles([User::ROL_ADMINISTRADOR]);
//        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'progresivas'])->syncRoles([User::ROL_ADMINISTRADOR]);

    }
}
