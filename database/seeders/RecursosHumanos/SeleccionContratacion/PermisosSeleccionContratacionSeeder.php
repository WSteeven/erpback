<?php /** @noinspection ALL */

/** @noinspection PhpUndefinedMethodInspection */

namespace Database\Seeders\RecursosHumanos\SeleccionContratacion;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
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
         * Módulo Seleccion y Contratacion
         ********************************/
        $empleado = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);
        $rrhh = Role::firstOrCreate(['name' => User::ROL_RECURSOS_HUMANOS]);
        $administrador = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR]);
        $gerente = Role::firstOrCreate(['name' => User::ROL_GERENTE]);
        $coordinador = Role::firstOrCreate(['name' => User::ROL_COORDINADOR]);
        $medico = Role::firstOrCreate(['name' => User::ROL_MEDICO]);

        // Módulo Seleccion y Contratacion
        Permission::firstOrCreate(['name' => Permisos::VER . 'modulo_seleccion_contratacion'])->syncRoles([$rrhh, $administrador, $gerente]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_seleccion_contratacion'])->syncRoles([$rrhh, $administrador, $gerente]);
        // solicitud de puestos
        Permission::firstOrCreate(['name' => Permisos::VER . 'rrhh_solicitudes_nuevas_vacantes'])->syncRoles([$rrhh, $administrador, $gerente]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rrhh_solicitudes_nuevas_vacantes'])->syncRoles([$rrhh, $administrador, $gerente]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'rrhh_solicitudes_nuevas_vacantes'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rrhh_solicitudes_nuevas_vacantes'])->syncRoles([$rrhh, $administrador, $gerente]);
        Permission::firstOrCreate(['name' => Permisos::AUTORIZAR . 'rrhh_solicitudes_nuevas_vacantes'])->syncRoles([$gerente]);

        // Vacantes
        Permission::firstOrCreate(['name' => Permisos::VER . 'rrhh_vacantes'])->syncRoles([$rrhh, $administrador, $gerente]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rrhh_vacantes'])->syncRoles([$rrhh, $administrador, $gerente]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'rrhh_vacantes'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rrhh_vacantes'])->syncRoles([$rrhh, $administrador, $gerente]);

        // Postulaciones
        Permission::firstOrCreate(['name' => Permisos::VER . 'rrhh_postulaciones'])->syncRoles([$rrhh, $administrador, $medico, $gerente]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rrhh_postulaciones'])->syncRoles([$rrhh, $administrador, $medico, $gerente]);
        // Permission::firstOrCreate(['name' => Permisos::CREAR . 'rrhh_postulaciones'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rrhh_postulaciones'])->syncRoles([$rrhh, $administrador, $gerente]);

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

        // áreas de conocimientos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rrhh_areas_conocimientos'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'rrhh_areas_conocimientos'])->syncRoles([$rrhh, $administrador, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'rrhh_areas_conocimientos'])->syncRoles([$rrhh, $administrador, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rrhh_areas_conocimientos'])->syncRoles([$rrhh, $administrador]);

        // modalidades
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rrhh_modalidades_trabajo'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'rrhh_modalidades_trabajo'])->syncRoles([$rrhh, $administrador, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'rrhh_modalidades_trabajo'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rrhh_modalidades_trabajo'])->syncRoles([$rrhh, $administrador]);

        // bancos de postulantes
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rrhh_bancos_postulantes'])->syncRoles([$rrhh, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'rrhh_bancos_postulantes'])->syncRoles([$rrhh, $administrador, $empleado]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'rrhh_bancos_postulantes'])->syncRoles([$rrhh, $administrador]);

        // Permisos de registrar examenes de postulantes
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'rrhh_examenes_postulantes'])->syncRoles($medico);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rrhh_examenes_postulantes'])->syncRoles($medico);


        // Permiso para ver los usuarios externos
        Permission::firstOrCreate(['name' => Permisos::VER . 'usuarios_externos'])->syncRoles([$rrhh, $administrador]);

        // otros permisos
        Permission::firstOrCreate(['name' => Permisos::VER . 'tipos_discapacidades'])->syncRoles($rrhh);


    }
}
