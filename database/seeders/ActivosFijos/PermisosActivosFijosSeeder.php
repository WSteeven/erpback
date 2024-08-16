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
     * @return void
     */
    public function run()
    {
        /***************************
         * Modulo de Activos Fijos
         ***************************/
        $activos_fijos = Role::firstOrCreate(['name' => User::ROL_ACTIVOS_FIJOS]);
        $empleado = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);

        // Modulo activos fijos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_activos_fijos'])->syncRoles([$activos_fijos, $empleado]);

        // Control de activos fijos
        Permission::firstOrCreate(['name' => Permisos::VER . 'control_activos_fijos'])->syncRoles([$activos_fijos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'control_activos_fijos'])->syncRoles([$activos_fijos]);

        // Categorias motivos consumo activos fijos
        Permission::firstOrCreate(['name' => Permisos::VER . 'categorias_motivos_consumo_activos_fijos'])->syncRoles([$activos_fijos]);

        // Motivos consumo activos fijos
        Permission::firstOrCreate(['name' => Permisos::VER . 'motivos_consumo_activos_fijos'])->syncRoles([$activos_fijos]);

        // Seguimiento consumo activos fijos
        Permission::firstOrCreate(['name' => Permisos::VER . 'seguimiento_consumo_activos_fijos'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'seguimiento_consumo_activos_fijos'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'seguimiento_consumo_activos_fijos'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'seguimiento_consumo_activos_fijos'])->syncRoles([$empleado]);
    }
}
