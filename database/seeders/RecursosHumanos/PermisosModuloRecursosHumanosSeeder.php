<?php /** @noinspection ALL */

namespace Database\Seeders\RecursosHumanos;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Config\Permisos;

class PermisosModuloRecursosHumanosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\RecursosHumanos\PermisosModuloRecursosHumanosSeeder"
     * @return void
     */
    public function run()
    {
        $admin = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR]);
        $empleado = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);
        $rrhh = Role::firstOrCreate(['name' => User::ROL_RECURSOS_HUMANOS]);

        // solicitud de vacaciones
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'solicitudes_vacaciones'])->syncRoles([$rrhh, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'solicitudes_vacaciones'])->syncRoles([$rrhh, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'solicitudes_vacaciones'])->syncRoles([$rrhh, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'solicitudes_vacaciones'])->syncRoles([$rrhh, $empleado]);

        //vacaciones
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'vacaciones'])->syncRoles([$rrhh, $admin]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'vacaciones'])->syncRoles([$rrhh, $empleado]);
//        Permission::firstOrCreate(['name' => Permisos::CREAR . 'vacaciones'])->syncRoles([$rrhh]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'vacaciones'])->syncRoles([$rrhh]);

        //planificadores
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'planificadores'])->syncRoles([$rrhh, $admin]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'planificadores'])->syncRoles([$rrhh, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'planificadores'])->syncRoles([$rrhh, $admin]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'planificadores'])->syncRoles([$rrhh, $admin]);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'planificadores'])->syncRoles([$admin]);

        // descuentos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'descuentos'])->syncRoles([$rrhh, $admin]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'descuentos'])->syncRoles([$rrhh, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'descuentos'])->syncRoles([$rrhh]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'descuentos'])->syncRoles([$rrhh]);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'descuentos'])->syncRoles([$admin]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'valores_cargados_roles'])->syncRoles([$rrhh, $admin]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'valores_cargados_roles'])->syncRoles([$rrhh, $admin]);



        Permission::firstOrCreate(['name' => Permisos::BOTON . 'finalizar_rol_pago'])->syncRoles([$rrhh, $admin]);
        Permission::firstOrCreate(['name' => 'puede.ver.campo.cash'])->syncRoles([$rrhh, $admin]);
        Permission::firstOrCreate(['name' => 'puede.ver.campo.enviar_rol_pago'])->syncRoles([$rrhh, $admin]);
        Permission::firstOrCreate(['name' => Permisos::VER.'rol_pago'])->syncRoles([$rrhh, $admin]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR.'rol_pago'])->syncRoles([$rrhh, $admin]);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR.'rol_pago'])->syncRoles([$rrhh, $admin]);
        // Botones de accionHeader
        Permission::firstOrCreate(['name' => Permisos::BOTON . 'agregar_empleados'])->syncRoles([$rrhh, $admin]);
        Permission::firstOrCreate(['name' => Permisos::BOTON . 'agregar_empleado_rol'])->syncRoles([$rrhh, $admin]);
        Permission::firstOrCreate(['name' => Permisos::BOTON . 'ejecutar_rol_pago'])->syncRoles([$rrhh, $admin]);
        Permission::firstOrCreate(['name' => Permisos::BOTON . 'finalizar_rol_pago'])->syncRoles([$rrhh, $admin]);
        Permission::firstOrCreate(['name' => Permisos::BOTON . 'actualizar_rol_pago'])->syncRoles([$rrhh, $admin]);



    }
}
