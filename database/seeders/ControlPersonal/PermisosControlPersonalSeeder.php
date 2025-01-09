<?php

namespace Database\Seeders\ControlPersonal;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Config\Permisos;

class PermisosControlPersonalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=Database\Seeders\ControlPersonal\PermisosControlPersonalSeeder
     *
     * @return void
     */
    public function run()
    {
        /***************************
         * Modulo de Control Personal
         ***************************/
        $control_personal = Role::firstOrCreate(['name' => User::ROL_RECURSOS_HUMANOS]);
        $empleado = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);
        $coordinador = Role::firstOrCreate(['name' => User::ROL_COORDINADOR]);
        $administrador = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR]);

        // Asistencia
        Permission::firstOrCreate(['name' => Permisos::VER . 'asistencia'])->syncRoles([$control_personal, $empleado, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'asistencia'])->syncRoles([$control_personal, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'asistencia'])->syncRoles([$control_personal, $administrador]);

        // Atrasos

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'atrasos'])->syncRoles([$control_personal, $empleado, $administrador], $coordinador);
        Permission::firstOrCreate(['name' => Permisos::VER . 'atrasos'])->syncRoles([$control_personal, $empleado, $administrador], $coordinador);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'atrasos'])->syncRoles([$empleado, $control_personal, $administrador, $coordinador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'atrasos'])->syncRoles([$control_personal, $administrador, $coordinador]);

        // Horarios de Entrada
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'horario_laboral'])->syncRoles([$control_personal, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'horario_laboral'])->syncRoles([$control_personal, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'horario_laboral'])->syncRoles([$control_personal, $administrador]);

        // Opcional: Permisos globales para reportes o gestión completa
        //Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_control_personal'])->syncRoles([$control_personal, $administrador]);
    }
}
