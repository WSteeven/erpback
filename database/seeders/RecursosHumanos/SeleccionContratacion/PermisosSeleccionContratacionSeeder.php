<?php

namespace Database\Seeders\RecursosHumanos\SeleccionContratacion;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Src\Config\Permisos;

class PermisosSeleccionContratacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=Database\Seeders\RecursosHumanos\SeleccionContratacion\PermisosSeleccionContratacionSeeder
     *
     * @return void
     */
    public function run()
    {
        /*********************************
         * Modulo Seleccion y Contratacion
         ********************************/
        $empleado = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);
        $rrhh = Role::firstOrCreate(['name' => User::ROL_RECURSOS_HUMANOS]);
        $administrador = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR]);
        $gerente = Role::firstOrCreate(['name' => User::ROL_GERENTE]);
        $coordinador = Role::firstOrCreate(['name' => User::ROL_COORDINADOR]);

        // Modulo Seleccion y Contratacion
        Permission::firstOrCreate(['name' => Permisos::VER . 'modulo.seleccion_contratacion'])->syncRoles([$rrhh, $administrador, $gerente]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo.seleccion_contratacion'])->syncRoles([$rrhh, $administrador, $gerente]);
        // solicitud de puestos
        Permission::firstOrCreate(['name' => Permisos::VER . 'rrhh_solicitudes_puestos_empleos'])->syncRoles([$rrhh, $administrador, $gerente]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rrhh_solicitudes_puestos_empleos'])->syncRoles([$rrhh, $administrador, $gerente]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'rrhh_solicitudes_puestos_empleos'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rrhh_solicitudes_puestos_empleos'])->syncRoles([$rrhh, $administrador, $gerente]);
        // Permission::firstOrCreate(['name' => Permisos::AUTORIZAR . 'rrhh_solicitudes_puestos_empleos'])->syncRoles([$gerente]);
        
        // Vacantes
        Permission::firstOrCreate(['name' => Permisos::VER . 'rrhh_vacantes'])->syncRoles([$rrhh, $administrador, $gerente]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rrhh_vacantes'])->syncRoles([$rrhh, $administrador, $gerente]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'rrhh_vacantes'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rrhh_vacantes'])->syncRoles([$rrhh, $administrador, $gerente]);
        
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'formaciones_academicas'])->syncRoles([$coordinador, $rrhh]);
        // publicar de puestos de trabajo
        Permission::firstOrCreate(['name' => Permisos::VER . 'publicacion_puesto_empleo'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'publicacion_puesto_empleo'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'publicacion_puesto_empleo'])->syncRoles([$rrhh, $administrador]);
        
        // tipos de puestos de trabajo        
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rrhh_tipos_puestos'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'rrhh_tipos_puestos'])->syncRoles([$rrhh, $administrador, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'rrhh_tipos_puestos'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rrhh_tipos_puestos'])->syncRoles([$rrhh, $administrador]);

        // areas de conocimientos        
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rrhh_areas_conocimientos'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'rrhh_areas_conocimientos'])->syncRoles([$rrhh, $administrador, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'rrhh_areas_conocimientos'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rrhh_areas_conocimientos'])->syncRoles([$rrhh, $administrador]);
    }
}
