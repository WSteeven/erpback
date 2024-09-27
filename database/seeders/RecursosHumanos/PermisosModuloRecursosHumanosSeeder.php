<?php /** @noinspection ALL */

namespace Database\Seeders\RecursosHumanos;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PermisosModuloRecursosHumanosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=Database\Seeders\RecursosHumanos\PermisosModuloRecursosHumanosSeeder
     * @return void
     */
    public function run()
    {
        $empleado = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);
        $rrhh = Role::firstOrCreate(['name' => User::ROL_RECURSOS_HUMANOS]);

        // solicitud de vacaciones
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'solicitud_vacaciones'])->syncRoles([$rrhh, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'solicitud_vacaciones'])->syncRoles([$rrhh, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'solicitud_vacaciones'])->syncRoles([$rrhh, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'solicitud_vacaciones'])->syncRoles([$rrhh, $empleado]);
    }
}
