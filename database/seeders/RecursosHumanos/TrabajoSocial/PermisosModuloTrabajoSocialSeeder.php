<?php /** @noinspection  ALL */

namespace Database\Seeders\RecursosHumanos\TrabajoSocial;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Config\Permisos;

class PermisosModuloTrabajoSocialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=Database\Seeders\RecursosHumanos\TrabajoSocial\PermisosModuloTrabajoSocialSeeder
     *
     * @return void
     */
    public function run()
    {
        /*********************************
         * Módulo Trabajo Social
         ********************************/
        $empleado = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);
        $trabajador_social = Role::firstOrCreate(['name' => User::ROL_TRABAJADOR_SOCIAL]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_trabajo_social'])->syncRoles([$trabajador_social]);

        // fichas socioeconomicas
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'fichas_socioeconomicas'])->syncRoles([$trabajador_social]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'fichas_socioeconomicas'])->syncRoles([$empleado, $trabajador_social]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'fichas_socioeconomicas'])->syncRoles([$trabajador_social]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'fichas_socioeconomicas'])->syncRoles([$trabajador_social]);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'fichas_socioeconomicas'])->syncRoles([$trabajador_social]);

        // visitas domiciliarias
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'visitas_domiciliarias'])->syncRoles([$trabajador_social]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'visitas_domiciliarias'])->syncRoles([$empleado, $trabajador_social]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'visitas_domiciliarias'])->syncRoles([$trabajador_social]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'visitas_domiciliarias'])->syncRoles([$trabajador_social]);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'visitas_domiciliarias'])->syncRoles([$trabajador_social]);


    }
}
