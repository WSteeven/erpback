<?php

namespace Database\Seeders\SSO;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Config\Permisos;

class PermisosSSOSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\SSO\PermisosSSOSeeder"
     * @return void
     */
    public function run()
    {
        $empleado = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);
        $sso = Role::firstOrCreate(['name' => User::ROL_SSO]);
        $contabilidad = Role::firstOrCreate(['name' => User::ROL_CONTABILIDAD]);
        $rrhh = Role::firstOrCreate(['name' => User::ROL_RECURSOS_HUMANOS]);
        $medico = Role::firstOrCreate(['name' => User::ROL_MEDICO]);
        // $bodega = Role::firstOrCreate(['name' => User::ROL_BODEGA]);

        // Modulo SSO
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_sso'])->syncRoles([$empleado]);
        // Incidentes
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'incidentes'])->syncRoles($empleado);
        Permission::firstOrCreate(['name' => Permisos::VER . 'incidentes'])->syncRoles($empleado);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'incidentes'])->syncRoles($empleado);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'incidentes'])->syncRoles($sso);
        // Inspecciones
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'inspecciones'])->syncRoles($sso);
        Permission::firstOrCreate(['name' => Permisos::VER . 'inspecciones'])->syncRoles($sso);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'inspecciones'])->syncRoles($sso);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'inspecciones'])->syncRoles($sso);
        // Seguimiento incidentes
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'seguimiento_incidentes'])->syncRoles($sso);
        Permission::firstOrCreate(['name' => Permisos::VER . 'seguimiento_incidentes'])->syncRoles($sso);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'seguimiento_incidentes'])->syncRoles($sso);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'seguimiento_incidentes'])->syncRoles($sso);
        // Accidentes
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'accidentes'])->syncRoles($empleado);
        Permission::firstOrCreate(['name' => Permisos::VER . 'accidentes'])->syncRoles($empleado);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'accidentes'])->syncRoles($empleado);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'accidentes'])->syncRoles($sso);
        // Solicitudes descuentos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'solicitudes_descuentos'])->syncRoles([$sso, $contabilidad]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'solicitudes_descuentos'])->syncRoles([$sso, $contabilidad]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'solicitudes_descuentos'])->syncRoles($sso);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'solicitudes_descuentos'])->syncRoles([$sso, $contabilidad]);
        // Certificaciones
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'certificaciones'])->syncRoles($sso);
        Permission::firstOrCreate(['name' => Permisos::VER . 'certificaciones'])->syncRoles($sso);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'certificaciones'])->syncRoles($sso);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'certificaciones'])->syncRoles($sso);
        // Certificaciones empleados
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'certificaciones_empleados'])->syncRoles($sso);
        Permission::firstOrCreate(['name' => Permisos::VER . 'certificaciones_empleados'])->syncRoles($sso);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'certificaciones_empleados'])->syncRoles($sso);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'certificaciones_empleados'])->syncRoles($sso);

        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'precios_unitarios_solicitudes_descuentos'])->syncRoles($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::CONFIRMAR . 'descuento_realizado_solicitudes_descuentos'])->syncRoles($rrhh);

        Permission::firstOrCreate(['name' => Permisos::VER . 'cies'])->syncRoles([$sso, $medico]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'consultas_medicas'])->syncRoles([$sso, $medico]);
    }
}
