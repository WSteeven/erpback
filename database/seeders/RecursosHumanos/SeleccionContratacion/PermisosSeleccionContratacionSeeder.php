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
        $rrhh = Role::firstOrCreate(['name' => User::ROL_RECURSOS_HUMANOS]);
        $administrador = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR]);
        $gerente = Role::firstOrCreate(['name' => User::ROL_GERENTE]);
        $coordinador = Role::firstOrCreate(['name' => User::ROL_COORDINADOR]);

        // Modulo Seleccion y Contratacion
        Permission::firstOrCreate(['name' => Permisos::VER . 'modulo.seleccion_contratacion'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo.seleccion_contratacion'])->syncRoles([$rrhh, $administrador]);
        // solicitud de puestos
        Permission::firstOrCreate(['name' => Permisos::VER . 'solicitudes_puestos'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'solicitudes_puestos'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'solicitudes_puestos'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'solicitudes_puestos'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::AUTORIZAR . 'solicitudes_puestos'])->syncRoles([$gerente]);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'conocimientos'])->syncRoles([$coordinador, $rrhh]);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'formaciones_academicas'])->syncRoles([$coordinador, $rrhh]);
        // publicar de puestos de trabajo
        Permission::firstOrCreate(['name' => Permisos::VER . 'publicacion_puesto_empleo'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'publicacion_puesto_empleo'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'publicacion_puesto_empleo'])->syncRoles([$rrhh, $administrador]);
        // tipos de puestos de trabajo        
        Permission::firstOrCreate(['name' => Permisos::VER . 'tipos_puestos_trabajos'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'tipos_puestos_trabajos'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . '.tipos_puestos_trabajos'])->syncRoles([$rrhh, $administrador]);
    }
}
