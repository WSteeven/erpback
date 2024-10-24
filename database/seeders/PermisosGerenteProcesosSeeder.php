<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Src\Config\Permisos;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $gerente_procesos = Role::firstOrCreate(['name' => User::ROL_GERENTE_PROCESOS]);

        Permission::firstOrCreate(['name' => Permisos::VER . 'tablero'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'perfil'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'configuracion'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'tablero_personal'])->syncRoles([$gerente_procesos]);

        // Dashboard
        Permission::firstOrCreate(['name' => Permisos::VER . 'dashboard_ordenes_compras'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'dashboard_ventas_empresa'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'dashboard_ventas_empresa'])->syncRoles([$gerente_procesos]);

        // Modulos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_medico'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_intranet'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_activos_fijos'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_tareas'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_bodega'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_compras'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_recursos_humanos'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_seleccion_contratacion'])->syncRoles([$gerente_procesos]);

        // Reportes
        Permission::firstOrCreate(['name' => Permisos::VER . 'reporte_solicitud_fondo'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'reporte_solicitud_fondo'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'reporte_ordenes_compras'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reportes_modulo_ventas'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::VER . 'empleados'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'empleados'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::AUTORIZAR . 'prestamo_empresarial'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'prestamo_empresarial'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rol_pago_mes'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'rol_pago_mes'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::AUTORIZAR . 'rol_pago_mes'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rol_pago'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'rol_pago'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ELABORAR . 'rol_pago'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::VER . 'campo.empleado'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'preordenes_compras'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'preordenes_compras'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'preordenes_compras'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'preordenes_compras'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'ordenes_compras'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'ordenes_compras'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'ordenes_compras'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'ordenes_compras'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::VER . 'proformas'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'proformas'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'proformas'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::AUTORIZAR . 'proformas'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'umbral_fondos_rotativos'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'umbral_fondos_rotativos'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'ingreso_rol_pago'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'ingreso_rol_pago'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::VER . 'rrhh_solicitudes_nuevas_vacantes'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rrhh_solicitudes_nuevas_vacantes'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rrhh_solicitudes_nuevas_vacantes'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::AUTORIZAR . 'rrhh_solicitudes_nuevas_vacantes'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::VER . 'rrhh_vacantes'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rrhh_vacantes'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rrhh_vacantes'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::VER . 'rrhh_postulaciones'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rrhh_postulaciones'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rrhh_postulaciones'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'vacacion'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'prefacturas'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'prefacturas'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::AUTORIZAR . 'ordenes_compras'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'ventas'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'gasto'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'gasto'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'gasto'])->syncRoles([$gerente_procesos]);

        // ver materiales empleados
        // pedidos, devoluciones, ordenes compra
        // ingresos, preingresos, egresos, transferencias, inventario, comprobantes, reportes bodega, ver

        Permission::firstOrCreate(['name' => Permisos::VER . 'materiales_empleados'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'materiales_empleados'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'pedidos'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'pedidos'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'pedidos'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'pedidos'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'devoluciones'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'devoluciones'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'devoluciones'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'devoluciones'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'ordenes_compras'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'ordenes_compras'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'ordenes_compras'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'ordenes_compras'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'transacciones_ingresos'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'transacciones_ingresos'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'transacciones_egresos'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'transacciones_egresos'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'preingresos_materiales'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'preingresos_materiales'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'inventarios'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'inventarios'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'comprobantes_egresos'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'comprobantes_egresos'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reportes_bodega'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'reportes_bodega'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'transferencia_producto_empleado'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'transferencia_producto_empleado'])->syncRoles([$gerente_procesos]);

        // Tareas
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reportes_materiales_utilizados'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'reportes_materiales_utilizados'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'alimentacion_grupo'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'alimentacion_grupo'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'alimentacion_grupo'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'alimentacion_grupo'])->syncRoles([$gerente_procesos]);

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

        // Tickets
        Permission::firstOrCreate(['name' => Permisos::VER . 'tipos_tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'tipos_tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'tipos_tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'tipos_tickets'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::VER . 'categorias_tipos_tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'categorias_tipos_tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'categorias_tipos_tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'categorias_tipos_tickets'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::VER . 'motivos_cancelados_tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'motivos_cancelados_tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'motivos_cancelados_tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'motivos_cancelados_tickets'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::VER . 'motivos_pausas_tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'motivos_pausas_tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'motivos_pausas_tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'motivos_pausas_tickets'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::VER . 'dashboard_tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'dashboard_tickets'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'categorias_tipos_tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'tipos_tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'motivos_pausas_tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'motivos_cancelados_tickets'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'tickets'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'tickets_asignados'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'tickets_asignados'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'tickets_asignados'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'tickets_asignados'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'tickets_asignados'])->syncRoles([$gerente_procesos]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_tickets'])->syncRoles([$gerente_procesos]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'modulo_tickets'])->syncRoles([$gerente_procesos]);
    }
}
