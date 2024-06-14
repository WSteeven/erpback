<?php

namespace Database\Seeders\Vehiculos;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Config\Permisos;

class PermisosModuloVehiculosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $empleado = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);
        $rrhh = Role::firstOrCreate(['name' => User::ROL_RECURSOS_HUMANOS]);
        $mecanico = Role::firstOrCreate(['name' => User::MECANICO_GENERAL]);
        $chofer = Role::firstOrCreate(['name' => User::CHOFER]);

        //En este contexto admin es el ADMINISTRADOR DE VEHICULOS
        $admin = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR_VEHICULOS]);

        //Modulo vehiculos
        Permission::firstOrCreate(['name' => Permisos::VER . 'modulo_vehiculos'])->syncRoles([$admin, $chofer]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_vehiculos'])->syncRoles([$admin, $chofer]);

        //Historial Vehiculos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'historial_vehiculos'])->syncRoles([$admin, $mecanico]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'historial_vehiculos'])->syncRoles([$admin, $mecanico]);

        //Reportes Vehiculos
        Permission::firstOrCreate(['name' => Permisos::VER . 'reportes_vehiculos'])->syncRoles([$admin, $mecanico]);

        //Vehiculos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'vehiculos'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'vehiculos'])->syncRoles([$admin, $chofer, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'vehiculos'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'vehiculos'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'vehiculos'])->syncRoles([$admin]);

        //Conductores
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'conductores'])->syncRoles([$admin, $rrhh]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'conductores'])->syncRoles([$admin, $rrhh, $chofer, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'conductores'])->syncRoles([$admin, $rrhh]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'conductores'])->syncRoles([$admin, $rrhh]);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'conductores'])->syncRoles($admin);

        // Multas de conductores
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'multas_conductores'])->syncRoles([$admin, $rrhh]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'multas_conductores'])->syncRoles([$admin, $rrhh, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'multas_conductores'])->syncRoles($admin);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'multas_conductores'])->syncRoles($admin);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'multas_conductores'])->syncRoles($admin);

        // Asignaciones de Vehiculos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'asignaciones_vehiculos'])->syncRoles([$admin, $chofer]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'asignaciones_vehiculos'])->syncRoles([$admin, $chofer]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'asignaciones_vehiculos'])->syncRoles([$admin, $chofer]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'asignaciones_vehiculos'])->syncRoles([$admin, $chofer]);

        // Transferencias de Vehiculos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'transferencias_vehiculos'])->syncRoles([$admin, $chofer]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'transferencias_vehiculos'])->syncRoles([$admin, $chofer]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'transferencias_vehiculos'])->syncRoles($chofer);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'transferencias_vehiculos'])->syncRoles($chofer);

        // Tipos de Vehiculos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'tipos_vehiculos'])->syncRoles($admin);
        Permission::firstOrCreate(['name' => Permisos::VER . 'tipos_vehiculos'])->syncRoles([$admin, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'tipos_vehiculos'])->syncRoles($admin);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'tipos_vehiculos'])->syncRoles($admin);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'tipos_vehiculos'])->syncRoles($admin);

        // Combustibles
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'combustibles'])->syncRoles($admin);
        Permission::firstOrCreate(['name' => Permisos::VER . 'combustibles'])->syncRoles([$admin, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'combustibles'])->syncRoles($admin);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'combustibles'])->syncRoles($admin);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'combustibles'])->syncRoles($admin);

        // Tanqueos Vehiculos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'tanqueos_vehiculos'])->syncRoles($admin, $chofer);
        Permission::firstOrCreate(['name' => Permisos::VER . 'tanqueos_vehiculos'])->syncRoles([$admin, $empleado, $chofer]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'tanqueos_vehiculos'])->syncRoles($admin, $chofer);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'tanqueos_vehiculos'])->syncRoles($admin, $chofer);

        // Bitacoras Vehiculos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'bitacoras_vehiculos'])->syncRoles($admin, $chofer);
        Permission::firstOrCreate(['name' => Permisos::VER . 'bitacoras_vehiculos'])->syncRoles([$admin, $empleado, $chofer]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'bitacoras_vehiculos'])->syncRoles($admin, $chofer);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'bitacoras_vehiculos'])->syncRoles($admin, $chofer);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'bitacoras_vehiculos'])->syncRoles($admin);

        // Registros Incidentes
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'registros_incidentes'])->syncRoles($admin);
        Permission::firstOrCreate(['name' => Permisos::VER . 'registros_incidentes'])->syncRoles($admin);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'registros_incidentes'])->syncRoles($admin);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'registros_incidentes'])->syncRoles($admin);

        // Ordenes Reparaciones
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'ordenes_reparaciones'])->syncRoles($admin, $chofer);
        Permission::firstOrCreate(['name' => Permisos::VER . 'ordenes_reparaciones'])->syncRoles($admin, $chofer);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'ordenes_reparaciones'])->syncRoles($admin, $chofer);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'ordenes_reparaciones'])->syncRoles($admin, $chofer);

        // Seguros Vehiculares
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'seguros_vehiculares'])->syncRoles($admin);
        Permission::firstOrCreate(['name' => Permisos::VER . 'seguros_vehiculares'])->syncRoles($admin);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'seguros_vehiculares'])->syncRoles($admin);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'seguros_vehiculares'])->syncRoles($admin);

        // Matriculas Vehiculos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'matriculas_vehiculos'])->syncRoles($admin);
        Permission::firstOrCreate(['name' => Permisos::VER . 'matriculas_vehiculos'])->syncRoles($admin);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'matriculas_vehiculos'])->syncRoles($admin);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'matriculas_vehiculos'])->syncRoles($admin);

        // Mantenimientos Vehiculos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'mantenimientos_vehiculos'])->syncRoles([$admin, $mecanico]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'mantenimientos_vehiculos'])->syncRoles([$admin, $mecanico]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'mantenimientos_vehiculos'])->syncRoles([$admin, $mecanico]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'mantenimientos_vehiculos'])->syncRoles([$admin, $mecanico]);

        // Servicios Mantenimientos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'servicios_mantenimientos'])->syncRoles([$admin, $mecanico]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'servicios_mantenimientos'])->syncRoles([$admin, $mecanico, $chofer]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'servicios_mantenimientos'])->syncRoles([$admin, $mecanico]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'servicios_mantenimientos'])->syncRoles([$admin, $mecanico]);

        // Planes de Mantenimientos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'planes_mantenimientos'])->syncRoles([$admin, $mecanico]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'planes_mantenimientos'])->syncRoles([$admin, $mecanico]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'planes_mantenimientos'])->syncRoles([$admin, $mecanico]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'planes_mantenimientos'])->syncRoles([$admin, $mecanico]);
    }
}
