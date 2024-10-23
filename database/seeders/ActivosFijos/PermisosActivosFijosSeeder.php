<?php

namespace Database\Seeders\ActivosFijos;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Config\Permisos;

class PermisosActivosFijosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=Database\Seeders\ActivosFijos\PermisosActivosFijosSeeder
     * php artisan db:seed --class=Database\\Seeders\\ActivosFijos\\PermisosActivosFijosSeeder -> CPanel
     * @return void
     */
    public function run()
    {
        /***************************
         * Modulo de Activos Fijos
         ***************************/
        $activos_fijos = Role::firstOrCreate(['name' => User::ROL_ACTIVOS_FIJOS]);
        $empleado = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);
        $administrador = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR]);

        // Modulo activos fijos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_activos_fijos'])->syncRoles([$activos_fijos, $empleado, $administrador]);

        // Control de activos fijos
        Permission::firstOrCreate(['name' => Permisos::VER . 'control_activos_fijos'])->syncRoles([$activos_fijos, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'control_activos_fijos'])->syncRoles([$activos_fijos, $administrador]);

        // Categorias motivos consumo activos fijos
        Permission::firstOrCreate(['name' => Permisos::VER . 'categorias_motivos_consumo_activos_fijos'])->syncRoles([$activos_fijos, $administrador]);

        // Motivos consumo activos fijos
        Permission::firstOrCreate(['name' => Permisos::VER . 'motivos_consumo_activos_fijos'])->syncRoles([$activos_fijos, $administrador]);

        // Seguimiento consumo activos fijos
        Permission::firstOrCreate(['name' => Permisos::VER . 'seguimiento_consumo_activos_fijos'])->syncRoles([$empleado, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'seguimiento_consumo_activos_fijos'])->syncRoles([$empleado, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'seguimiento_consumo_activos_fijos'])->syncRoles([$empleado, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'seguimiento_consumo_activos_fijos'])->syncRoles([$empleado, $administrador]);

        // Transferencia activos fijos - Pendiente de desarrollar
        /* Permission::firstOrCreate(['name' => Permisos::VER . 'transferencia_activos_fijos'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'transferencia_activos_fijos'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'transferencia_activos_fijos'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'transferencia_activos_fijos'])->syncRoles([$empleado]); */
    }
}
