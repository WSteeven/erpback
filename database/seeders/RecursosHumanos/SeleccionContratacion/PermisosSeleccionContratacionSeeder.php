<?php

namespace Database\Seeders\RecursosHumanos\SeleccionContratacion;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermisosSeleccionContratacionSeeder extends Seeder
{
    const CREAR = 'puede.crear';
    const ACCEDER = 'puede.acceder'; // Formulario
    const VER = 'puede.ver'; // Consultar index y show
    const EDITAR = 'puede.editar';
    const RECHAZAR = 'puede.rechazar';
    const AUTORIZAR = 'puede.autorizar';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*********************************
         Modulo Seleccion y Contratacion
         ********************************/
        $rrhh = Role::firstOrCreate(['name' => User::ROL_RECURSOS_HUMANOS]);
        $administrador = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR]);
        // Modulo Seleccion y Contratacion
        Permission::firstOrCreate(['name' => self::VER . '.modulo.seleccion_contratacion'])->syncRoles([$rrhh,$administrador]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.modulo.seleccion_contratacion'])->syncRoles([$rrhh,$administrador]);
        // solicitud de puestos
        Permission::firstOrCreate(['name' => self::VER . '.solicitud_puesto_empleo'])->syncRoles([$rrhh,$administrador]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.solicitud_puesto_empleo'])->syncRoles([$rrhh,$administrador]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.solicitud_puesto_empleo'])->syncRoles([$rrhh,$administrador]);
        // publicar de puestos de trabajo
        Permission::firstOrCreate(['name' => self::VER . '.publicacion_puesto_empleo'])->syncRoles([$rrhh,$administrador]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.publicacion_puesto_empleo'])->syncRoles([$rrhh,$administrador]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.publicacion_puesto_empleo'])->syncRoles([$rrhh,$administrador]);
        // tipos de puestos de trabajo
        Permission::firstOrCreate(['name' => self::VER . '.tipos_puestos_trabajos'])->syncRoles([$rrhh,$administrador]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.tipos_puestos_trabajos'])->syncRoles([$rrhh,$administrador]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.tipos_puestos_trabajos'])->syncRoles([$rrhh,$administrador]);
    }
}
