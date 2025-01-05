<?php

namespace Database\Seeders\Tareas;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Config\Permisos;

class PermisosTareasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\Tareas\PermisosTareasSeeder"
     * @return void
     */
    public function run()
    {
        $jefeTecnico = Role::firstOrCreate(['name' => User::ROL_JEFE_TECNICO]);
        $bodega = Role::firstOrCreate(['name' => User::ROL_BODEGA]);
        $coordinadorBodega = Role::firstOrCreate(['name' => User::ROL_COORDINADOR_BODEGA]);
        $coordinador = Role::firstOrCreate(['name' => User::ROL_COORDINADOR]); // Coordinador Tecnico
        $coordinadorBackup = Role::firstOrCreate(['name' => User::ROL_COORDINADOR_BACKUP]);
        $administrador = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reportes_materiales_utilizados'])->syncRoles([$jefeTecnico, $bodega, $coordinadorBodega, $coordinador, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'reportes_materiales_utilizados'])->syncRoles([$jefeTecnico, $bodega, $coordinadorBodega, $coordinador, $administrador]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'alimentacion_grupo'])->syncRoles([$jefeTecnico, $coordinador, $coordinadorBackup, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'alimentacion_grupo'])->syncRoles([$jefeTecnico, $coordinador, $coordinadorBackup, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'alimentacion_grupo'])->syncRoles([$jefeTecnico, $coordinador, $coordinadorBackup, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'alimentacion_grupo'])->syncRoles([$jefeTecnico, $coordinador, $coordinadorBackup, $administrador]);

        /* PERMISOS DEL JEFE TECNICO
         Permission::firstOrCreate(['name' => Permisos::VER . 'tablero'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::VER . 'perfil'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::VER . 'configuracion'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::VER . 'clientes'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::CREAR . 'clientes'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::EDITAR . 'clientes'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::VER . 'empresas'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::CREAR . 'empresas'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::EDITAR . 'empresas'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::VER . 'proveedores'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::VER . 'motivos'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::VER . 'pedidos'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::VER . 'proyectos'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::CREAR . 'proyectos'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::EDITAR . 'proyectos'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'proyectos'])->syncRoles([$gerente_procesos]);

Permission::firstOrCreate(['name' => Permisos::VER . 'tareas'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::CREAR . 'tareas'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::EDITAR . 'tareas'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'tareas'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::VER . 'modulo_tareas'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::VER . 'reporte_movilizacion_subtarea'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::VER . 'tablero_personal'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::VER . 'tipos_trabajos'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::CREAR . 'tipos_trabajos'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::EDITAR . 'tipos_trabajos'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'tipos_trabajos'])->syncRoles([$gerente_procesos]);

Permission::firstOrCreate(['name' => Permisos::VER . 'grupos'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::CREAR . 'grupos'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::EDITAR . 'grupos'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::VER . 'clientes_finales'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::CREAR . 'clientes_finales'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::EDITAR . 'clientes_finales'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'clientes_finales'])->syncRoles([$gerente_procesos]);

Permission::firstOrCreate(['name' => Permisos::VER . 'rutas_tareas'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::CREAR . 'rutas_tareas'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rutas_tareas'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'rutas_tareas'])->syncRoles([$gerente_procesos]);

Permission::firstOrCreate(['name' => Permisos::VER . 'gasto_coordinador'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::CREAR . 'gasto_coordinador'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::VER . 'motivo_gasto'])->syncRoles([$gerente_procesos]);

Permission::firstOrCreate(['name' => Permisos::VER . 'reporte_trabajos_realizados'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::CREAR . 'reporte_trabajos_realizados'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::EDITAR . 'reporte_trabajos_realizados'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'reporte_trabajos_realizados'])->syncRoles([$gerente_procesos]);

Permission::firstOrCreate(['name' => Permisos::VER . 'hoja_control_trabajos'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::CREAR . 'hoja_control_trabajos'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::EDITAR . 'hoja_control_trabajos'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'hoja_control_trabajos'])->syncRoles([$gerente_procesos]);

Permission::firstOrCreate(['name' => Permisos::VER . 'motivos_pausas'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::CREAR . 'motivos_pausas'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::EDITAR . 'motivos_pausas'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'motivos_pausas'])->syncRoles([$gerente_procesos]);

Permission::firstOrCreate(['name' => Permisos::VER . 'motivos_pendientes'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::CREAR . 'motivos_pendientes'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::EDITAR . 'motivos_pendientes'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'motivos_pendientes'])->syncRoles([$gerente_procesos]);

Permission::firstOrCreate(['name' => Permisos::VER . 'motivos_suspendidos'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::CREAR . 'motivos_suspendidos'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::EDITAR . 'motivos_suspendidos'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'motivos_suspendidos'])->syncRoles([$gerente_procesos]);

Permission::firstOrCreate(['name' => Permisos::VER . 'monitor_subtareas'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::CREAR . 'monitor_subtareas'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::EDITAR . 'monitor_subtareas'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'monitor_subtareas'])->syncRoles([$gerente_procesos]);

Permission::firstOrCreate(['name' => Permisos::VER . 'reportes_bodega'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::EDITAR . 'devoluciones'])->syncRoles([$gerente_procesos]);

Permission::firstOrCreate(['name' => Permisos::VER . 'causa_intervencion'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::CREAR . 'causa_intervencion'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::EDITAR . 'causa_intervencion'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'causa_intervencion'])->syncRoles([$gerente_procesos]);

Permission::firstOrCreate(['name' => Permisos::VER . 'reportes_proyecto'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::CREAR . 'reportes_proyecto'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::EDITAR . 'reportes_proyecto'])->syncRoles([$gerente_procesos]);
Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'reportes_proyecto'])->syncRoles([$gerente_procesos]);

         */
    }
}
