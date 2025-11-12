<?php

namespace Database\Seeders\Conecel;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Config\Permisos;

class PermisosGestionTareasConecelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\Conecel\PermisosGestionTareasConecelSeeder"
     * @return void
     */
    public function run()
    {
        /*********************************
         * Módulo Gestion Tareas Conecel
         ********************************/
        $empleado = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);
        $administrador = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR]);
        $gerente = Role::firstOrCreate(['name' => User::ROL_GERENTE]);
        $coordinador = Role::firstOrCreate(['name' => User::ROL_COORDINADOR]);
        $jefeTecnico = Role::firstOrCreate(['name' => User::JEFE_TECNICO]);

        // Módulo Gestion Tareas Conecel
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_gestion_conecel'])->syncRoles([$administrador, $coordinador, $jefeTecnico, $gerente]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'dashboard_tareas_conecel'])->syncRoles([$administrador, $coordinador, $jefeTecnico, $gerente]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'tareas_conecel'])->syncRoles([$administrador, $coordinador, $jefeTecnico, $gerente]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'tareas_conecel'])->syncRoles([$administrador, $coordinador, $jefeTecnico, $gerente, $empleado]);
//        Permission::firstOrCreate(['name' => Permisos::CREAR . 'tareas_conecel'])->syncRoles([$administrador, $coordinador, $jefeTecnico, ]);
//        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'tareas_conecel'])->syncRoles([$administrador, $coordinador, $jefeTecnico]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'vehiculos_cuadrillas_conecel'])->syncRoles([$administrador, $coordinador, $jefeTecnico, $gerente]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'vehiculos_cuadrillas_conecel'])->syncRoles([$administrador, $coordinador, $jefeTecnico]);





    }
}
