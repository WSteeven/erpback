<?php

namespace Database\Seeders\Sistema;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Config\Permisos;

class PermisosAccederFrontendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $empleado = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);
        $empleado_saliente = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);
        $consulta = Role::firstOrCreate(['name' => User::ROL_CONSULTA]);
        $administrador = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR]);
        $administrativo = Role::firstOrCreate(['name' => User::ROL_ADMINISTRATIVO]);
        $administrador_fondos = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR_FONDOS]);
        $contabilidad = Role::firstOrCreate(['name' => User::ROL_CONTABILIDAD]);
        $coordinador = Role::firstOrCreate(['name' => User::ROL_COORDINADOR]);
        $coordinador_backup = Role::firstOrCreate(['name' => User::ROL_COORDINADOR_BACKUP]);
        $fiscalizador = Role::firstOrCreate(['name' => User::ROL_FISCALIZADOR]);
        $jefe_tecnico = Role::firstOrCreate(['name' => User::ROL_JEFE_TECNICO]);
        $rrhh = Role::firstOrCreate(['name' => User::ROL_RECURSOS_HUMANOS]);
        $gerente = Role::firstOrCreate(['name' => User::ROL_GERENTE]);
        $coordinador_bodega = Role::firstOrCreate(['name' => User::ROL_COORDINADOR_BODEGA]);
        $auditor_interno = Role::firstOrCreate(['name' => User::ROL_AUDITOR]);
        $compras = Role::firstOrCreate(['name' => User::ROL_COMPRAS]);
        $jefe_departamento = Role::firstOrCreate(['name' => User::ROL_JEFE_DEPARTAMENTO]);
        $medico = Role::firstOrCreate(['name' => User::ROL_MEDICO]);
        $bodega = Role::firstOrCreate(['name' => User::ROL_BODEGA]);
        $bodega_telconet = Role::firstOrCreate(['name' => User::ROL_BODEGA_TELCONET]);
        $medico = Role::firstOrCreate(['name' => User::ROL_MEDICO]);
        $activos_fijos = Role::firstOrCreate(['name' => User::ROL_ACTIVOS_FIJOS]);
        $jefe_coordinacion_nedetel = Role::firstOrCreate(['name' => User::ROL_JEFE_COORDINACION_NEDETEL]);
        $administrador_tickets_1 = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR_TICKETS_1]);
        $administrador_tickets_2 = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR_TICKETS_2]);

        /*****************
         * Modulo tareas
         *****************/
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_tareas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'dashboard_tareas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'monitor_subtareas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'proyectos'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'tareas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'alimentacion_grupo'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reporte_movilizacion_subtarea'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'clientes_finales'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reportes_modulo_tareas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reportes_materiales_utilizados'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reportes_modulo_tareas'])->syncRoles([$empleado]);

        /********************
         * Modulo de tickets
         ********************/
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_tickets'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'dashboard_tickets'])->syncRoles([$administrador, $coordinador_backup, $consulta, $jefe_coordinacion_nedetel, $auditor_interno, $administrador_tickets_1]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'tickets'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'tickets_asignados'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'categorias_tipos_tickets'])->syncRoles([$empleado, $administrador_tickets_1, $administrador_tickets_2]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'tipos_tickets'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'motivos_pausas_tickets'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'motivos_cancelados_tickets'])->syncRoles([$empleado]);

        /*******************
         * Modulo de bodega
         *******************/
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_bodega'])->syncRoles([$empleado, $administrador, $activos_fijos, $administrativo, $bodega, $contabilidad, $coordinador, $fiscalizador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'dashboard_bodega'])->syncRoles([$administrador, $bodega, $consulta, $bodega_telconet, $coordinador_bodega, $auditor_interno]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'materiales_empleados'])->syncRoles([$administrador, $bodega, $contabilidad, $coordinador, $jefe_tecnico, $rrhh, $fiscalizador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'inventarios'])->syncRoles([$bodega, $contabilidad, $consulta, $empleado_saliente]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'devoluciones'])->syncRoles([$empleado, $activos_fijos, $contabilidad, $coordinador, $coordinador_backup, $consulta, $empleado_saliente]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'pedidos'])->syncRoles([$empleado, $activos_fijos, $administrativo, $bodega, $contabilidad, $coordinador, $jefe_tecnico, $coordinador_backup]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'traspasos'])->syncRoles([$consulta, $bodega]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'comprobantes_egresos'])->syncRoles([$administrador, $rrhh, $fiscalizador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reportes_bodega'])->syncRoles([$coordinador, $jefe_tecnico, $fiscalizador, $jefe_coordinacion_nedetel, $auditor_interno]);

        /*****************************
         * Modulo de fondos rotativos
         *****************************/
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'fondo'])->syncRoles([$empleado, $consulta]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'gasto'])->syncRoles([$empleado, $contabilidad, $consulta, $fiscalizador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'gasto_coordinador'])->syncRoles([$contabilidad, $coordinador, $jefe_tecnico, $rrhh, $fiscalizador, $jefe_coordinacion_nedetel, $auditor_interno]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'anular_gasto'])->syncRoles([$administrador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'menu.detalle_fondo'])->syncRoles([$administrador_fondos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'detalle_fondo'])->syncRoles([$administrador_fondos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'sub_detalle_fondo'])->syncRoles([$administrador_fondos]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'menu.solicitud_fondo'])->syncRoles([$contabilidad]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'motivo_gasto'])->syncRoles([$contabilidad]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'menu.saldos'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'acreditacion'])->syncRoles([$contabilidad, $administrador, $administrador_fondos, $consulta]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'umbral_fondos_rotativos'])->syncRoles([$contabilidad, $gerente]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'acreditacion_semana'])->syncRoles([$administrador, $contabilidad]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reporte_valores_fondos_rotativos'])->syncRoles([$administrador, $contabilidad]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reporte_fondo_fecha'])->syncRoles([$empleado, $consulta]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reporte_autorizaciones'])->syncRoles([$empleado, $consulta]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reporte_saldo_actual'])->syncRoles([$empleado, $consulta]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reporte_consolidado'])->syncRoles([$contabilidad, $administrador, $coordinador, $consulta]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reporte_consolidado_filtrado'])->syncRoles([$contabilidad, $administrador, $coordinador, $consulta]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reporte_solicitud_fondo'])->syncRoles([$contabilidad, $gerente, $consulta]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reporte_contabilidad'])->syncRoles([$contabilidad, $consulta, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'ajustes_saldos'])->syncRoles([$contabilidad, $consulta, $administrador]);

        // compras y proveedres
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_compras'])->syncRoles([$jefe_coordinacion_nedetel, $medico, $administrador, $compras, $coordinador, $coordinador_bodega, $contabilidad, $jefe_tecnico, $jefe_departamento, $auditor_interno]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'dashboard_ordenes_compras'])->syncRoles([$administrador, $contabilidad, $consulta, $auditor_interno]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'proveedores'])->syncRoles([$compras, $jefe_tecnico, $contabilidad, $consulta]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'categorias_ofertas'])->syncRoles([$contabilidad]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reportes_proveedores'])->syncRoles([$administrador, $compras, $contabilidad, $fiscalizador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reporte_proveedores'])->syncRoles([$contabilidad, $administrador, $compras]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reporte_ordenes_compras'])->syncRoles([$contabilidad, $administrador]);

        // Modulo de ventas jp
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_ventas'])->syncRoles([$contabilidad, $administrador, $coordinador, $fiscalizador, $auditor_interno]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'dashboard_ventas_empresa'])->syncRoles([$administrador, $contabilidad, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'proformas'])->syncRoles([$administrador, $compras, $contabilidad, $gerente, $fiscalizador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'prefacturas'])->syncRoles([$contabilidad, $administrador, $compras, $coordinador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reportes_modulo_ventas'])->syncRoles([$administrador, $compras]);


        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'configuracion_general'])->syncRoles([$administrador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'tipos_trabajos'])->syncRoles([$jefe_tecnico, $administrador, $jefe_coordinacion_nedetel]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'causas_intervenciones'])->syncRoles([$jefe_tecnico, $administrador, $jefe_coordinacion_nedetel]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'motivos_pausas'])->syncRoles([$jefe_tecnico, $administrador, $jefe_coordinacion_nedetel]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'motivos_suspendidos'])->syncRoles([$jefe_tecnico, $administrador, $jefe_coordinacion_nedetel]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'rutas_tareas'])->syncRoles([$jefe_tecnico, $administrador, $jefe_coordinacion_nedetel]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_administracion'])->syncRoles([$administrador, $activos_fijos, $bodega, $bodega_telconet, $auditor_interno]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'autorizaciones'])->syncRoles([$administrador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'codigos_clientes'])->syncRoles([$administrador, $bodega]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'estados_transacciones'])->syncRoles([$administrador, $coordinador_bodega]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'hilos'])->syncRoles([$administrador, $bodega]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'tipos_fibras'])->syncRoles([$administrador, $bodega]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'motivos'])->syncRoles([$administrador, $coordinador_bodega]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'unidades_medidas'])->syncRoles([$administrador, $coordinador_bodega]);

        // Roles y permisos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'roles'])->syncRoles([$administrador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'permisos'])->syncRoles([$administrador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'permisos_roles'])->syncRoles([$administrador]);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'permisos_usuarios'])->syncRoles([$administrador]);
    }
}
