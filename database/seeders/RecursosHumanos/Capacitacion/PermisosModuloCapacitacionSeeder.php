<?php /** @noinspection ALL */

namespace Database\Seeders\RecursosHumanos\Capacitacion;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Config\Permisos;

class PermisosModuloCapacitacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *  php artisan db:seed --class=Database\Seeders\RecursosHumanos\Capacitacion\PermisosModuloCapacitacionSeeder
     * @return void
     */
    public function run()
    {
        /*********************************
         * Módulo Capacitacion de Personal
         ********************************/
        $empleado = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);
        $rrhh = Role::firstOrCreate(['name' => User::ROL_RECURSOS_HUMANOS]);
        $administrador = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR]);
        $gerente = Role::firstOrCreate(['name' => User::ROL_GERENTE]);
        $coordinador = Role::firstOrCreate(['name' => User::ROL_COORDINADOR]);
        $medico = Role::firstOrCreate(['name' => User::ROL_MEDICO]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_capacitacion_personal'])->syncRoles([$rrhh, $administrador]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rrhh_capacitacion_formularios'])->syncRoles([$rrhh, $administrador, $coordinador]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'rrhh_capacitacion_formularios'])->syncRoles([$rrhh, $administrador, $coordinador]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'rrhh_capacitacion_formularios'])->syncRoles([$rrhh, $administrador, $coordinador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rrhh_capacitacion_formularios'])->syncRoles([$rrhh, $administrador, $coordinador]);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'rrhh_capacitacion_formularios'])->syncRoles([$rrhh, $administrador, $coordinador]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rrhh_capacitacion_evaluaciones_desempeno'])->syncRoles([$rrhh, $administrador, $coordinador]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'rrhh_capacitacion_evaluaciones_desempeno'])->syncRoles([$rrhh, $administrador, $coordinador]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'rrhh_capacitacion_evaluaciones_desempeno'])->syncRoles([$rrhh, $administrador, $coordinador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rrhh_capacitacion_evaluaciones_desempeno'])->syncRoles([$rrhh, $administrador, $coordinador]);


    }
}
