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
        // Modulo Seleccion y Contratacion
        Permission::firstOrCreate(['name' => self::VER . '.modulo.seleccion_contratacion'])->syncRoles([$rrhh]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.modulo.seleccion_contratacion'])->syncRoles([$rrhh]);
        // solicitud de puestos
        Permission::firstOrCreate(['name' => self::VER . '.solicitud_puesto_empleo'])->syncRoles([$rrhh]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.solicitud_puesto_empleo'])->syncRoles([$rrhh]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.solicitud_puesto_empleo'])->syncRoles([$rrhh]);
        // tipos de puestos de trabajo
        Permission::firstOrCreate(['name' => self::VER . '.tipos_puestos_trabajos'])->syncRoles([$rrhh]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.tipos_puestos_trabajos'])->syncRoles([$rrhh]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.tipos_puestos_trabajos'])->syncRoles([$rrhh]);
    }
}
