<?php

namespace Database\Seeders\Tareas;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermisosJefeTecnicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\Tareas\PermisosJefeTecnicoSeeder"
     * @return void
     */
    public function run()
    {
        // Definir el rol
        $jefeTecnico = Role::firstOrCreate(['name' => User::ROL_JEFE_TECNICO]);

        // Lista de permisos a crear
        $permisos = [
            "puede.ver.clientes",
            "puede.crear.clientes",
            "puede.editar.clientes",
            "puede.ver.codigos_clientes",
            "puede.crear.codigos_clientes",
            "puede.editar.codigos_clientes",
            "puede.crear.compras",
            "puede.ver.modulo_tareas",
            "puede.ver.movilizacion_subtarea",
            "puede.ver.reporte_movilizacion_subtarea",
            "puede.ver.subtarea_asignada",
            "puede.ver.proyectos",
            "puede.crear.proyectos",
            "puede.editar.proyectos",
            "puede.ver.tareas",
            "puede.crear.tareas",
            "puede.editar.tareas",
            "puede.eliminar.tareas",
            "puede.ver.subtareas",
            "puede.crear.subtareas",
            "puede.editar.subtareas",
            "puede.eliminar.subtareas",
            "puede.ver.tipos_trabajos",
            "puede.crear.tipos_trabajos",
            "puede.editar.tipos_trabajos",
            "puede.ver.clientes_finales",
            "puede.crear.clientes_finales",
            "puede.editar.clientes_finales",
            "puede.eliminar.clientes_finales",
            "puede.ver.rutas_tareas",
            "puede.crear.rutas_tareas",
            "puede.editar.rutas_tareas",
            "puede.eliminar.rutas_tareas",
            "puede.ver.reporte_fondo_fecha",
            "puede.ver.reporte_saldo_actual",
            "puede.ver.reporte_autorizaciones",
            "puede.ver.reporte_consolidado",
            "puede.ver.reporte_consolidado_filtrado",
            "puede.ver.reporte_contabilidad",
            "puede.ver.reporte_solicitud_fondo",
            "puede.buscar.saldo.usuarios",
            "puede.ver.reporte_trabajos_realizados",
            "puede.ver.motivos_pausas",
            "puede.crear.motivos_pausas",
            "puede.editar.motivos_pausas",
            "puede.ver.motivos_suspendidos",
            "puede.crear.motivos_suspendidos",
            "puede.editar.motivos_suspendidos",
            "puede.ver.monitor_subtareas",
            "puede.crear.monitor_subtareas",
            "puede.editar.monitor_subtareas",
            "puede.eliminar.monitor_subtareas",
            "puede.ver.causas_intervenciones",
            "puede.crear.causas_intervenciones",
            "puede.editar.causas_intervenciones",
            "puede.eliminar.causas_intervenciones",
            "puede.ver.reportes_modulo_tareas",
            "puede.crear.reportes_modulo_tareas",
            "puede.editar.reportes_modulo_tareas",
            "puede.eliminar.reportes_modulo_tareas",
            "puede.ver.materiales_empleados",
            "puede.ver.dashboard_tareas",
            "puede.ver.modulo_compras",
            "puede.autorizar.devoluciones",
            "puede.crear.prefacturas",
            "puede.editar.prefacturas",
            "puede.ver.modulo_ventas",
            "puede.crear.preingresos_materiales",
            "puede.editar.preingresos_materiales",
            "puede.acceder.grupos",
            "puede.acceder.centros_costos",
            "puede.ver.centros_costos",
            "puede.crear.centros_costos",
            "puede.editar.centros_costos",
            "puede.acceder.subcentros_costos",
            "puede.ver.subcentros_costos",
            "puede.crear.subcentros_costos",
            "puede.editar.subcentros_costos",
            "puede.activar.centros_costos",
            "puede.desactivar.centros_costos",
            "puede.editar.transferencia_producto_empleado",
            "puede.pausar.subtareas",
            "puede.ejecutar.subtareas",
            "puede.acceder.clientes",
            "puede.acceder.reportes_materiales_utilizados",
            "puede.ver.reportes_materiales_utilizados",
            "puede.acceder.modulo_tareas",
            "puede.acceder.dashboard_tareas",
            "puede.acceder.monitor_subtareas",
            "puede.acceder.proyectos",
            "puede.acceder.tareas",
            "puede.acceder.reporte_movilizacion_subtarea",
            "puede.acceder.clientes_finales",
            "puede.acceder.reportes_modulo_tareas",
            "puede.acceder.materiales_empleados",
            "puede.acceder.gasto_coordinador",
            "puede.acceder.reporte_valores_fondos_rotativos",
            "puede.acceder.reporte_fondo_fecha",
            "puede.acceder.reporte_autorizaciones",
            "puede.acceder.reporte_saldo_actual",
            "puede.acceder.reporte_consolidado",
            "puede.acceder.reporte_consolidado_filtrado",
            "puede.acceder.reporte_solicitud_fondo",
            "puede.acceder.reporte_contabilidad",
            "puede.acceder.proveedores",
            "puede.acceder.modulo_ventas",
            "puede.acceder.reportes_modulo_ventas",
            "puede.acceder.tipos_trabajos",
            "puede.acceder.causas_intervenciones",
            "puede.acceder.motivos_pausas",
            "puede.acceder.motivos_suspendidos",
            "puede.acceder.rutas_tareas",
            "puede.acceder.codigos_clientes",
            "puede.autorizar.transferencia_producto_empleado",
        ];

        // Crear permisos y asignarlos al rol
        foreach ($permisos as $permiso) {
            $permisoCreado = Permission::firstOrCreate(['name' => $permiso]);
            $jefeTecnico->givePermissionTo($permisoCreado);
        }
    }
}
