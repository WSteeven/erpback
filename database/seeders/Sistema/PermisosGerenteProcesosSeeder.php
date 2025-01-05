<?php

namespace Database\Seeders\Sistema;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Src\Config\Permisos;

class PermisosGerenteProcesosSeeder extends Seeder
{
    // php artisan db:seed --class="Database\Seeders\Sistema\PermisosGerenteProcesosSeeder"
    public function run()
    {
        $gerente_procesos = Role::firstOrCreate(['name' => User::ROL_GERENTE_PROCESOS]);
        $contabilidad = Role::firstOrCreate(['name' => User::ROL_CONTABILIDAD]);

        // Crear y asignar permisos manualmente utilizando la estructura de Permisos
        /*Permission::firstOrCreate(['name' => Permisos::VER . 'tablero_personal'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::VER . 'gasto_coordinador'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'saldo'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'saldo'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::VER . 'campo.empleado.fondo_rotativo'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::BUSCAR . 'saldo.usuarios'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::VER . 'menu.solicitud_fondo'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'concepto_ingreso'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'descuentos_generales'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'descuento_ley'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'multa'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rol_pago_mes'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::VER . 'detalles_viaticos'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'umbral_fondos_rotativos'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'acreditacion_semana'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::VER . 'acreditacion_semana'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'valor_acreditar'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::VER . 'modulo_compras'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'categorias_ofertas'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'datos_bancarios_proveedores'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'ajustes_saldos'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'monitor_subtareas'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'inventarios'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'devoluciones'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'pedidos'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'gasto'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'menu.solicitud_fondo'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'motivo_gasto'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'acreditacion'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reporte_valores_fondos_rotativos'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reporte_solicitud_fondo'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reporte_contabilidad'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'dashboard_ordenes_compras'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reportes_proveedores'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reporte_proveedores'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reporte_ordenes_compras'])->assignRole($contabilidad);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'dashboard_ventas_empresa'])->assignRole($contabilidad);*/

        Permission::firstOrCreate(['name' => Permisos::VER . 'tablero'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'perfil'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'configuracion'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'tablero_personal'])->assignRole($gerente_procesos);

        // Dashboard
        Permission::firstOrCreate(['name' => Permisos::VER . 'dashboard_ordenes_compras'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'dashboard_ventas_empresa'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'dashboard_ventas_empresa'])->assignRole($gerente_procesos);

        // Modulos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_medico'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_intranet'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_activos_fijos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_tareas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_bodega'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_compras'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_recursos_humanos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_seleccion_contratacion'])->assignRole($gerente_procesos);

        // Reportes
        Permission::firstOrCreate(['name' => Permisos::VER . 'reporte_solicitud_fondo'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'reporte_solicitud_fondo'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'reporte_ordenes_compras'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reportes_modulo_ventas'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'empleados'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'empleados'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::AUTORIZAR . 'prestamo_empresarial'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'prestamo_empresarial'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rol_pago_mes'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'rol_pago_mes'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::AUTORIZAR . 'rol_pago_mes'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rol_pago'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'rol_pago'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELABORAR . 'rol_pago'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'campo.empleado'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'preordenes_compras'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'preordenes_compras'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'preordenes_compras'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'preordenes_compras'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'ordenes_compras'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'ordenes_compras'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'ordenes_compras'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'ordenes_compras'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'proformas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'proformas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'proformas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::AUTORIZAR . 'proformas'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'umbral_fondos_rotativos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'umbral_fondos_rotativos'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'ingreso_rol_pago'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'ingreso_rol_pago'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'rrhh_solicitudes_nuevas_vacantes'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rrhh_solicitudes_nuevas_vacantes'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rrhh_solicitudes_nuevas_vacantes'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::AUTORIZAR . 'rrhh_solicitudes_nuevas_vacantes'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'rrhh_vacantes'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rrhh_vacantes'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rrhh_vacantes'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'rrhh_postulaciones'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rrhh_postulaciones'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rrhh_postulaciones'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'vacacion'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'prefacturas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'prefacturas'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::AUTORIZAR . 'ordenes_compras'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'ventas'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'gasto'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'gasto'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'gasto'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'materiales_empleados'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'materiales_empleados'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'pedidos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'pedidos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'pedidos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'pedidos'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'devoluciones'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'devoluciones'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'devoluciones'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'devoluciones'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'ordenes_compras'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'ordenes_compras'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'ordenes_compras'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'ordenes_compras'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'transacciones_ingresos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'transacciones_ingresos'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'transacciones_egresos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'transacciones_egresos'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'preingresos_materiales'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'preingresos_materiales'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'inventarios'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'inventarios'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'comprobantes_egresos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'comprobantes_egresos'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reportes_bodega'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'reportes_bodega'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'transferencia_producto_empleado'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'transferencia_producto_empleado'])->assignRole($gerente_procesos);

        // Tareas
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reportes_materiales_utilizados'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'reportes_materiales_utilizados'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'alimentacion_grupo'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'alimentacion_grupo'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'alimentacion_grupo'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'alimentacion_grupo'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'tablero'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'perfil'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'configuracion'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'clientes'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'clientes'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'clientes'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'empresas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'empresas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'empresas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'proveedores'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'motivos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'pedidos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'proyectos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'proyectos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'proyectos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'proyectos'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'tareas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'tareas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'tareas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'tareas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'modulo_tareas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'reporte_movilizacion_subtarea'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'tablero_personal'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'tipos_trabajos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'tipos_trabajos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'tipos_trabajos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'tipos_trabajos'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'grupos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'grupos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'grupos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'clientes_finales'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'clientes_finales'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'clientes_finales'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'clientes_finales'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'rutas_tareas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'rutas_tareas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'rutas_tareas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'rutas_tareas'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'gasto_coordinador'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'gasto_coordinador'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'motivo_gasto'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'reporte_trabajos_realizados'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'reporte_trabajos_realizados'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'reporte_trabajos_realizados'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'reporte_trabajos_realizados'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'hoja_control_trabajos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'hoja_control_trabajos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'hoja_control_trabajos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'hoja_control_trabajos'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'motivos_pausas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'motivos_pausas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'motivos_pausas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'motivos_pausas'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'motivos_pendientes'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'motivos_pendientes'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'motivos_pendientes'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'motivos_pendientes'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'motivos_suspendidos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'motivos_suspendidos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'motivos_suspendidos'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'motivos_suspendidos'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'monitor_subtareas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'monitor_subtareas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'monitor_subtareas'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'monitor_subtareas'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'reportes_bodega'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'devoluciones'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'causa_intervencion'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'causa_intervencion'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'causa_intervencion'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'causa_intervencion'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'reportes_proyecto'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'reportes_proyecto'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'reportes_proyecto'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'reportes_proyecto'])->assignRole($gerente_procesos);

        // Tickets
        Permission::firstOrCreate(['name' => Permisos::VER . 'tipos_tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'tipos_tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'tipos_tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'tipos_tickets'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'categorias_tipos_tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'categorias_tipos_tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'categorias_tipos_tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'categorias_tipos_tickets'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'motivos_cancelados_tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'motivos_cancelados_tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'motivos_cancelados_tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'motivos_cancelados_tickets'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'motivos_pausas_tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'motivos_pausas_tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'motivos_pausas_tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'motivos_pausas_tickets'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::VER . 'dashboard_tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'dashboard_tickets'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'categorias_tipos_tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'tipos_tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'motivos_pausas_tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'motivos_cancelados_tickets'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'tickets'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'tickets_asignados'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'tickets_asignados'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'tickets_asignados'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'tickets_asignados'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'tickets_asignados'])->assignRole($gerente_procesos);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_tickets'])->assignRole($gerente_procesos);
        Permission::firstOrCreate(['name' => Permisos::VER . 'modulo_tickets'])->assignRole($gerente_procesos);
    }
}
