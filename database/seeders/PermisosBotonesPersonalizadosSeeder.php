<?php /** @noinspection ALL */

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Config\Permisos;

class PermisosBotonesPersonalizadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=Database\Seeders\PermisosBotonesPersonalizadosSeeder
     *
     * @return void
     */
    public function run()
    {
        // Define roles
        $admin = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR]);
        $coordinador_bodega = Role::firstOrCreate(['name' => User::ROL_COORDINADOR_BODEGA]);
        $coordinador = Role::firstOrCreate(['name' => User::ROL_COORDINADOR]);
        $rrhh = Role::firstOrCreate(['name' => User::ROL_RECURSOS_HUMANOS]);
        $sso = Role::firstOrCreate(['name' => User::ROL_SSO]);



        // Define permisos
        Permission::firstOrCreate(['name' => Permisos::BOTON . 'modificar_stock.materiales_empleados'])->syncRoles([$coordinador_bodega]);
        Permission::firstOrCreate(['name' => Permisos::BOTON . 'activar.detalles'])->syncRoles([$coordinador_bodega, $admin]);
        Permission::firstOrCreate(['name' => Permisos::BOTON . 'desactivar.detalles'])->syncRoles([$coordinador_bodega, $admin]);
        Permission::firstOrCreate(['name' => Permisos::BOTON . 'plan_vacaciones.empleados'])->syncRoles([$coordinador_bodega, $admin, $rrhh, $coordinador, $sso]);
        Permission::firstOrCreate(['name' => Permisos::BOTON . 'finalizar.rol_pago'])->syncRoles([$coordinador_bodega, $admin, $rrhh, $coordinador, $sso]);
    }
}
