<?php

namespace Database\Seeders\ControlPersonal;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Config\Permisos;

class PermisosControlPersonalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\ControlPersonal\PermisosControlPersonalSeeder"
     *
     * @return void
     */
    public function run()
    {
        /***************************
         * Modulo de Control Personal
         ***************************/
        $rrhh = Role::firstOrCreate(['name' => User::ROL_RECURSOS_HUMANOS]);
        $empleado = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);
        $coordinador = Role::firstOrCreate(['name' => User::ROL_COORDINADOR]);
        $sso = Role::firstOrCreate(['name' => User::ROL_SSO]);
        $administrador = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR]);

        // Dashboard
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'dashboard_control_personal'])->syncRoles([$rrhh, $administrador, $coordinador, $sso]);


        // Asistencias
        Permission::firstOrCreate(['name' => Permisos::VER . 'asistencias'])->syncRoles([$rrhh, $empleado, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'asistencias'])->syncRoles([$rrhh, $administrador, $sso, $coordinador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'asistencias'])->syncRoles([$rrhh, $administrador]);

        // Atrasos

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'atrasos'])->syncRoles([$rrhh, $empleado, $administrador, $coordinador]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'atrasos'])->syncRoles([$rrhh, $empleado, $administrador, $coordinador]);
//        Permission::firstOrCreate(['name' => Permisos::CREAR . 'atrasos'])->syncRoles([$empleado, $control_personal, $administrador, $coordinador]); // no se puede crear atrasos, porque esos se registran automaticamente.
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'atrasos'])->syncRoles([$rrhh, $administrador, $empleado, $coordinador, $sso]);

        // Horarios de Entrada
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'horario_laboral'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'horario_laboral'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'horario_laboral'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'horario_laboral'])->syncRoles([$rrhh, $administrador]);

        // Opcional: Permisos globales para reportes o gestión completa
        //Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_control_personal'])->syncRoles([$control_personal, $administrador]);
    }
}
