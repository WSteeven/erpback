<?php /** @noinspection ALL */

namespace Database\Seeders\RecursosHumanos\Capacitacion;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Config\Permisos;

class PermisosCapacitacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *  php artisan db:seed --class=Database\Seeders\RecursosHumanos\Capacitacion\PermisosCapacitacionSeeder
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

    }
}
