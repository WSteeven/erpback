<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // -----------------
        // Roles
        // -----------------
        $administrador = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR]);
        $activos_fijos = Role::firstOrCreate(['name' => User::ROL_ACTIVOS_FIJOS]);
        $administrativo = Role::firstOrCreate(['name' => User::ROL_ADMINISTRATIVO]);
        $bodega = Role::firstOrCreate(['name' => User::ROL_BODEGA]);
        $compras = Role::firstOrCreate(['name' => User::ROL_COMPRAS]);
        $contabilidad = Role::firstOrCreate(['name' => User::ROL_CONTABILIDAD]);
        $coordinador = Role::firstOrCreate(['name' => User::ROL_COORDINADOR]);
        $empleado = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);
        $gerente = Role::firstOrCreate(['name' => User::ROL_GERENTE]);
        $jefe_tecnico = Role::firstOrCreate(['name' => User::ROL_JEFE_TECNICO]);
        $recursos_humanos = Role::firstOrCreate(['name' => User::ROL_RECURSOS_HUMANOS]);
        $soo = Role::firstOrCreate(['name' => User::ROL_SSO]);
        $fiscalizador = Role::firstOrCreate(['name' => User::ROL_FISCALIZADOR]);
        $administrador_fondos = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR_FONDOS]);

        // Roles de cuadrillas
        $tecnico_lider = Role::firstOrCreate(['name' => User::ROL_LIDER_DE_GRUPO]);
        // $tecnico_secretario = Role::firstOrCreate(['name' => User::ROL_SECRETARIO]);
        $autorizador = Role::firstOrCreate(['name' => User::ROL_AUTORIZADOR]);
        $tecnico = Role::firstOrCreate(['name' => User::ROL_TECNICO]);
        $coordinador_backup = Role::firstOrCreate(['name' => User::ROL_COORDINADOR_BACKUP]);

        // -----------------
        // Modulo de Sistema
        // -----------------
        // Tablero
        Permission::firstOrCreate(['name' => 'puede.ver.tablero'])->syncRoles([$coordinador, $coordinador_backup, $contabilidad, $bodega, $empleado, $jefe_tecnico, $gerente, $compras, $activos_fijos, $recursos_humanos]);
        // Perfil
        Permission::firstOrCreate(['name' => 'puede.ver.perfil'])->syncRoles([$coordinador, $coordinador_backup, $bodega, $empleado, $jefe_tecnico, $gerente, $compras, $activos_fijos]);
        // Administración
        Permission::firstOrCreate(['name' => 'puede.ver.modulo_administracion'])->syncRoles([$bodega, $activos_fijos]);
        // Configuracion
        Permission::firstOrCreate(['name' => 'puede.ver.configuracion'])->syncRoles([$coordinador, $coordinador_backup, $bodega, $empleado, $jefe_tecnico, $gerente, $compras, $activos_fijos]);


        // -----------------------
        // Modulo de Activos Fijos
        // -----------------------
        Permission::firstOrCreate(['name' => 'puede.ver.modulo_activos_fijos'])->syncRoles([$activos_fijos]);

        //Activos fijos
        Permission::firstOrCreate(['name' => 'puede.ver.activos_fijos'])->syncRoles([$activos_fijos]);
        Permission::firstOrCreate(['name' => 'puede.crear.activos_fijos'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.editar.activos_fijos'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.eliminar.activos_fijos'])->assignRole($activos_fijos);

        // -----------------
        // Modulo de Bodega
        // -----------------
        Permission::firstOrCreate(['name' => 'puede.ver.modulo_bodega'])->syncRoles([$activos_fijos, $administrativo, $bodega, $coordinador, $coordinador_backup,  $contabilidad, $empleado]);
        Permission::firstOrCreate(['name' => 'puede.ver.modulo_recursos_humanos'])->assignRole($recursos_humanos);
        // Autorizaciones
        Permission::firstOrCreate(['name' => 'puede.ver.autorizaciones'])->syncRoles([$activos_fijos, $bodega, $contabilidad, $coordinador, $coordinador_backup, $administrativo, $empleado]);
        Permission::firstOrCreate(['name' => 'puede.crear.autorizaciones'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.editar.autorizaciones'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.eliminar.autorizaciones'])->assignRole($activos_fijos);

        //Cargos
        Permission::firstOrCreate(['name' => 'puede.ver.cargos'])->syncRoles([$empleado, $recursos_humanos]);
        Permission::firstOrCreate(['name' => 'puede.crear.cargos'])->assignRole($recursos_humanos);
        Permission::firstOrCreate(['name' => 'puede.editar.cargos'])->assignRole($recursos_humanos);
        Permission::firstOrCreate(['name' => 'puede.eliminar.cargos'])->assignRole($recursos_humanos);

        //Categorias
        Permission::firstOrCreate(['name' => 'puede.ver.categorias'])->syncRoles([$activos_fijos, $bodega, $coordinador]);
        Permission::firstOrCreate(['name' => 'puede.crear.categorias'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.editar.categorias'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.eliminar.categorias'])->assignRole($activos_fijos);

        //Clientes
        Permission::firstOrCreate(['name' => 'puede.ver.clientes'])->syncRoles([$jefe_tecnico, $activos_fijos, $bodega, $coordinador, $coordinador_backup,  $empleado]);
        Permission::firstOrCreate(['name' => 'puede.crear.clientes'])->assignRole($jefe_tecnico);
        Permission::firstOrCreate(['name' => 'puede.editar.clientes'])->assignRole($jefe_tecnico);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.clientes'])->assignRole($jefe_tecnico);

        //Codigos de clientes
        Permission::firstOrCreate(['name' => 'puede.ver.codigos_clientes'])->syncRoles([$activos_fijos, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.crear.codigos_clientes'])->assignRole($jefe_tecnico);
        Permission::firstOrCreate(['name' => 'puede.editar.codigos_clientes'])->assignRole($jefe_tecnico);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.codigos_clientes'])->assignRole($jefe_tecnico);

        //Condiciones
        Permission::firstOrCreate(['name' => 'puede.ver.condiciones'])->syncRoles([$activos_fijos, $bodega, $empleado]);
        Permission::firstOrCreate(['name' => 'puede.crear.condiciones'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.editar.condiciones'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.eliminar.condiciones'])->assignRole($activos_fijos);

        //Condiciones
        Permission::firstOrCreate(['name' => 'puede.ver.control_stock'])->syncRoles([$activos_fijos, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.crear.control_stock'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.control_stock'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.eliminar.control_stock'])->assignRole($bodega);

        // Detalles de productos
        Permission::firstOrCreate(['name' => 'puede.ver.detalles'])->syncRoles([$empleado, $coordinador, $coordinador_backup, $activos_fijos,  $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.detalles'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.detalles'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.eliminar.detalles'])->assignRole($bodega);

        // Devolución
        Permission::firstOrCreate(['name' => 'puede.ver.devoluciones'])->syncRoles([$empleado, $coordinador, $coordinador_backup, $activos_fijos,  $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.devoluciones'])->syncRoles([$bodega, $empleado]);
        // Permission::firstOrCreate(['name' => 'puede.editar.devoluciones'])->syncRoles([$bodega, $empleado]);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.devoluciones'])->assignRole($bodega);

        // Empleados
        Permission::firstOrCreate(['name' => 'puede.ver.empleados'])->syncRoles([$recursos_humanos, $coordinador, $coordinador_backup, $activos_fijos, $bodega, $empleado]);
        Permission::firstOrCreate(['name' => 'puede.crear.empleados'])->assignRole($recursos_humanos);
        Permission::firstOrCreate(['name' => 'puede.editar.empleados'])->assignRole($recursos_humanos);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.empleados'])->assignRole($recursos_humanos);

        // Empresas
        Permission::firstOrCreate(['name' => 'puede.ver.empresas'])->syncRoles([$jefe_tecnico, $empleado, $activos_fijos, $coordinador]);
        Permission::firstOrCreate(['name' => 'puede.crear.empresas'])->syncRoles([$jefe_tecnico, $activos_fijos]);
        Permission::firstOrCreate(['name' => 'puede.editar.empresas'])->syncRoles([$jefe_tecnico, $activos_fijos]);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.empresas'])->assignRole($jefe_tecnico, $activos_fijos);

        //Estados de transacciones
        Permission::firstOrCreate(['name' => 'puede.ver.estados_transacciones'])->syncRoles([$activos_fijos, $bodega, $contabilidad, $coordinador, $coordinador_backup, $administrativo, $empleado]);
        Permission::firstOrCreate(['name' => 'puede.crear.estados_transacciones'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.editar.estados_transacciones'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.eliminar.estados_transacciones'])->assignRole($activos_fijos);

        // Hilos
        Permission::firstOrCreate(['name' => 'puede.ver.hilos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.crear.hilos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.hilos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.eliminar.hilos'])->assignRole($bodega);

        //Inventarios
        Permission::firstOrCreate(['name' => 'puede.ver.inventarios'])->syncRoles([$bodega, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.inventarios'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.inventarios'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.eliminar.inventarios'])->assignRole($bodega);
        //Marcas
        Permission::firstOrCreate(['name' => 'puede.ver.marcas'])->syncRoles($activos_fijos, $bodega);
        Permission::firstOrCreate(['name' => 'puede.crear.marcas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.marcas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.eliminar.marcas'])->assignRole($bodega);
        //Modelos
        Permission::firstOrCreate(['name' => 'puede.ver.modelos'])->syncRoles($activos_fijos, $bodega);
        Permission::firstOrCreate(['name' => 'puede.crear.modelos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.modelos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.eliminar.modelos'])->assignRole($bodega);
        //Movimientos de productos
        Permission::firstOrCreate(['name' => 'puede.ver.movimientos_productos'])->syncRoles($activos_fijos, $bodega);
        Permission::firstOrCreate(['name' => 'puede.crear.movimientos_productos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.movimientos_productos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.eliminar.movimientos_productos'])->assignRole($bodega);
        //Motivos
        Permission::firstOrCreate(['name' => 'puede.ver.motivos'])->syncRoles([$jefe_tecnico, $activos_fijos, $coordinador, $coordinador_backup, $bodega,  $contabilidad, $administrativo]);
        Permission::firstOrCreate(['name' => 'puede.crear.motivos'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.editar.motivos'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.eliminar.motivos'])->assignRole($activos_fijos);

        //Notificaciones
        Permission::firstOrCreate(['name' => 'puede.ver.notificaciones'])->assignRole($empleado);
        Permission::firstOrCreate(['name' => 'puede.crear.notificaciones'])->assignRole($empleado);
        Permission::firstOrCreate(['name' => 'puede.editar.notificaciones'])->assignRole($empleado);
        Permission::firstOrCreate(['name' => 'puede.eliminar.notificaciones'])->assignRole($empleado);

        //Pedidos
        Permission::firstOrCreate(['name' => 'puede.ver.pedidos'])->syncRoles([$empleado, $activos_fijos, $bodega,  $coordinador, $coordinador_backup, $administrativo, $contabilidad, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.pedidos'])->assignRole($empleado);
        Permission::firstOrCreate(['name' => 'puede.editar.pedidos'])->assignRole($empleado);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.pedidos'])->assignRole($empleado);

        //Perchas
        Permission::firstOrCreate(['name' => 'puede.ver.perchas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.crear.perchas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.perchas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.eliminar.perchas'])->assignRole($bodega);
        //Pisos
        Permission::firstOrCreate(['name' => 'puede.ver.pisos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.crear.pisos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.pisos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.eliminar.pisos'])->assignRole($bodega);

        //Prestamos
        Permission::firstOrCreate(['name' => 'puede.ver.prestamos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.crear.prestamos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.prestamos'])->assignRole($bodega);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.prestamos'])->assignRole($bodega);

        //Productos
        Permission::firstOrCreate(['name' => 'puede.ver.productos'])->syncRoles([$activos_fijos, $bodega, $empleado]);
        Permission::firstOrCreate(['name' => 'puede.crear.productos'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.editar.productos'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.eliminar.productos'])->assignRole($activos_fijos);

        //Productos en perchas
        Permission::firstOrCreate(['name' => 'puede.ver.productos_perchas'])->syncRoles([$activos_fijos, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.crear.productos_perchas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.productos_perchas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.eliminar.productos_perchas'])->assignRole($bodega);

        //Proveedores
        Permission::firstOrCreate(['name' => 'puede.ver.proveedores'])->syncRoles([$jefe_tecnico, $activos_fijos, $coordinador, $coordinador_backup, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.crear.proveedores'])->assignRole($compras);
        Permission::firstOrCreate(['name' => 'puede.editar.proveedores'])->assignRole($compras);
        Permission::firstOrCreate(['name' => 'puede.eliminar.proveedores'])->assignRole($compras);

        //Sucursales
        Permission::firstOrCreate(['name' => 'puede.ver.sucursales'])->syncRoles([$activos_fijos, $bodega, $recursos_humanos,  $contabilidad, $coordinador, $coordinador_backup, $administrativo, $empleado]);
        Permission::firstOrCreate(['name' => 'puede.crear.sucursales'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.editar.sucursales'])->assignRole($activos_fijos);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.sucursales'])->assignRole($activos_fijos);

        //Traspasos
        Permission::firstOrCreate(['name' => 'puede.ver.traspasos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.crear.traspasos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.traspasos'])->assignRole($bodega);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.traspasos'])->assignRole($bodega);

        //Transferencias
        Permission::firstOrCreate(['name' => 'puede.ver.transferencias'])->assignRole([$bodega, $activos_fijos]);
        Permission::firstOrCreate(['name' => 'puede.crear.transferencias'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.transferencias'])->syncRoles([$bodega, $activos_fijos]);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.transferencias'])->assignRole($bodega);

        //Tipos de fibras
        Permission::firstOrCreate(['name' => 'puede.ver.tipos_fibras'])->syncRoles([$coordinador, $coordinador_backup, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.crear.tipos_fibras'])->syncRoles([$coordinador, $coordinador_backup, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.editar.tipos_fibras'])->syncRoles([$coordinador, $coordinador_backup, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.tipos_fibras'])->syncRoles([$coordinador, $coordinador_backup, $bodega]);
        //Tipos de transacciones
        Permission::firstOrCreate(['name' => 'puede.ver.tipos_transacciones'])->syncRoles([$activos_fijos, $bodega,  $contabilidad, $coordinador, $coordinador_backup, $administrativo]);
        Permission::firstOrCreate(['name' => 'puede.crear.tipos_transacciones'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.editar.tipos_transacciones'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.eliminar.tipos_transacciones'])->assignRole($activos_fijos);
        //Transacciones
        Permission::firstOrCreate(['name' => 'puede.ver.transacciones_egresos'])->syncRoles([$bodega, $contabilidad, $coordinador]);
        Permission::firstOrCreate(['name' => 'puede.crear.transacciones_egresos'])->syncRoles([$bodega]);
        Permission::firstOrCreate(['name' => 'puede.editar.transacciones_egresos'])->syncRoles([$bodega, $coordinador]);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.transacciones_egresos'])->syncRoles([$bodega]);

        Permission::firstOrCreate(['name' => 'puede.ver.transacciones_ingresos'])->syncRoles([$bodega, $contabilidad, $coordinador]);
        Permission::firstOrCreate(['name' => 'puede.crear.transacciones_ingresos'])->syncRoles([$bodega]);
        Permission::firstOrCreate(['name' => 'puede.editar.transacciones_ingresos'])->syncRoles([$bodega, $coordinador]);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.transacciones_ingresos'])->syncRoles([$bodega]);

        Permission::firstOrCreate(['name' => 'puede.ver.transacciones'])->syncRoles([$bodega, $coordinador, $coordinador_backup,  $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.transacciones'])->syncRoles([$bodega, $coordinador, $coordinador_backup,  $administrativo, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.transacciones'])->syncRoles([$bodega]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.transacciones']); //->syncRoles([$coordinador, $coordinador_backup, $gerente, $jefe_tecnico]);
        // Permission::firstOrCreate(['name' => 'puede.autorizar.transacciones'])->syncRoles([$coordinador, $coordinador_backup, $gerente, $jefe_tecnico]);

        //Ubicaciones
        Permission::firstOrCreate(['name' => 'puede.ver.ubicaciones'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.crear.ubicaciones'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.ubicaciones'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.eliminar.ubicaciones'])->assignRole($bodega);

        //Ubicaciones
        Permission::firstOrCreate(['name' => 'puede.ver.unidades_medidas'])->syncRoles([$bodega, $coordinador, $coordinador_backup,  $contabilidad, $activos_fijos]);
        Permission::firstOrCreate(['name' => 'puede.crear.unidades_medidas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.unidades_medidas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.eliminar.unidades_medidas'])->assignRole($bodega);




        Permission::firstOrCreate(['name' => 'puede.ver.materiales'])->syncRoles([$coordinador, $coordinador_backup, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.crear.liquidacion'])->syncRoles([$coordinador, $coordinador_backup, $bodega]);

        Permission::firstOrCreate(['name' => 'puede.crear.compras'])->assignRole($compras);


        // -----------------
        // Modulo de Tareas
        // -----------------
        Permission::firstOrCreate(['name' => 'puede.ver.modulo_tareas'])->syncRoles([$coordinador, $coordinador_backup, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.ver.trabajo_agendado'])->syncRoles([$empleado]); //$tecnico_lider, $coordinador, $coordinador_backup, $administrativo]);
        Permission::firstOrCreate(['name' => 'puede.ver.movilizacion_subtarea'])->syncRoles([$empleado]); //reporte-movilizacion-subtarea
        Permission::firstOrCreate(['name' => 'puede.ver.reporte_movilizacion_subtarea'])->syncRoles([$coordinador, $coordinador_backup, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.ver.mi_bodega'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => 'puede.ver.subtarea_asignada'])->assignRole($tecnico_lider, $coordinador);
        Permission::firstOrCreate(['name' => 'puede.ver.control_avance'])->syncRoles([$coordinador]);
        Permission::firstOrCreate(['name' => 'puede.ver.gestionar_avances'])->syncRoles([$coordinador]);
        Permission::firstOrCreate(['name' => 'puede.ver.tablero_personal'])->syncRoles([$coordinador, $coordinador_backup, $bodega, $empleado, $jefe_tecnico, $gerente, $compras,  $activos_fijos, $administrativo, $recursos_humanos]);

        // Proyectos
        /**
         * El jefe tecnico y los coordinadores son los encargados de crear proyectos.
         * Dentro de cada proyecto van las tareas y dentro de las mismas van las subtareas.
         */
        Permission::firstOrCreate(['name' => 'puede.ver.proyectos'])->syncRoles([$jefe_tecnico, $coordinador]);
        Permission::firstOrCreate(['name' => 'puede.crear.proyectos'])->syncRoles([$jefe_tecnico, $coordinador]);
        Permission::firstOrCreate(['name' => 'puede.editar.proyectos'])->syncRoles([$jefe_tecnico, $coordinador]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.proyectos'])->syncRoles([$jefe_tecnico, $coordinador]);
        // Tareas
        /**
         * Los coordinadores son los encargados de crear las tareas
         */
        Permission::firstOrCreate(['name' => 'puede.ver.tareas'])->syncRoles([$coordinador, $coordinador_backup, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.tareas'])->syncRoles([$coordinador, $coordinador_backup, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.tareas'])->syncRoles([$coordinador, $coordinador_backup, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.tareas'])->syncRoles([$coordinador, $coordinador_backup, $jefe_tecnico]);
        // Subtareas
        /**
         * Los coordinadores son los encargados de crear las subtareas dentro de cada tarea
         */
        Permission::firstOrCreate(['name' => 'puede.ver.subtareas'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.crear.subtareas'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.editar.subtareas'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.eliminar.subtareas'])->assignRole($coordinador);
        // Tipos de trabajo
        /**
         * Los coordinadores son los encargados de crear los tipos de subtareas que ellos crean convenientes
         */
        Permission::firstOrCreate(['name' => 'puede.ver.tipos_trabajos'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.tipos_trabajos'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.tipos_trabajos'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.tipos_trabajos'])->syncRoles([$coordinador, $jefe_tecnico]);
        // Tendidos
        Permission::firstOrCreate(['name' => 'puede.ver.grupos'])->assignRole($recursos_humanos);
        Permission::firstOrCreate(['name' => 'puede.crear.grupos'])->assignRole($recursos_humanos);
        Permission::firstOrCreate(['name' => 'puede.editar.grupos'])->assignRole($recursos_humanos);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.grupos'])->assignRole($recursos_humanos);
        // Tipos elementos
        Permission::firstOrCreate(['name' => 'puede.ver.tipos_elementos'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.crear.tipos_elementos'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.editar.tipos_elementos'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.eliminar.tipos_elementos'])->assignRole($coordinador);
        // Control de asistencia
        /*Permission::firstOrCreate(['name' => 'puede.ver.control_asistencia'])->syncRoles([$coordinador]);
        Permission::firstOrCreate(['name' => 'puede.crear.control_asistencia'])->syncRoles([$tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.control_asistencia'])->syncRoles([$tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.control_asistencia'])->syncRoles([$tecnico]);*/
        // Control de progresivas
        Permission::firstOrCreate(['name' => 'puede.ver.control_tendidos'])->syncRoles([$coordinador]);
        Permission::firstOrCreate(['name' => 'puede.crear.control_tendidos'])->syncRoles([$tecnico_lider]);
        Permission::firstOrCreate(['name' => 'puede.editar.control_tendidos'])->syncRoles([$coordinador]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.control_tendidos'])->syncRoles([$tecnico_lider]);
        // Control de cambios
        Permission::firstOrCreate(['name' => 'puede.ver.control_cambios'])->syncRoles([$coordinador]);
        Permission::firstOrCreate(['name' => 'puede.crear.control_cambios'])->syncRoles([$coordinador]);
        Permission::firstOrCreate(['name' => 'puede.editar.control_cambios'])->syncRoles([$coordinador]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.control_cambios'])->syncRoles([$coordinador]);
        // Solicitud de materiales a bodega
        Permission::firstOrCreate(['name' => 'puede.ver.solicitud_materiales'])->syncRoles([$coordinador]);
        Permission::firstOrCreate(['name' => 'puede.crear.solicitud_materiales'])->syncRoles([$coordinador]);
        Permission::firstOrCreate(['name' => 'puede.editar.solicitud_materiales'])->syncRoles([$coordinador]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.solicitud_materiales'])->syncRoles([$coordinador]);
        // Reportes control de materiales
        Permission::firstOrCreate(['name' => 'puede.ver.reportes_control_materiales'])->syncRoles([$coordinador]);
        Permission::firstOrCreate(['name' => 'puede.crear.reportes_control_materiales'])->syncRoles([$coordinador]);
        Permission::firstOrCreate(['name' => 'puede.editar.reportes_control_materiales'])->syncRoles([$coordinador]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.reportes_control_materiales'])->syncRoles([$coordinador]);
        // Reportes control tendidos
        Permission::firstOrCreate(['name' => 'puede.ver.reportes_control_tendidos'])->syncRoles([$coordinador]);
        Permission::firstOrCreate(['name' => 'puede.crear.reportes_control_tendidos'])->syncRoles([$coordinador]);
        Permission::firstOrCreate(['name' => 'puede.editar.reportes_control_tendidos'])->syncRoles([$coordinador]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.reportes_control_tendidos'])->syncRoles([$coordinador]);
        // Clientes finales
        Permission::firstOrCreate(['name' => 'puede.ver.clientes_finales'])->syncRoles([$coordinador, $coordinador_backup, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.clientes_finales'])->syncRoles([$coordinador, $coordinador_backup, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.clientes_finales'])->syncRoles([$coordinador, $coordinador_backup, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.clientes_finales'])->syncRoles([$coordinador, $coordinador_backup, $jefe_tecnico]);
        // Rutas de tareas
        Permission::firstOrCreate(['name' => 'puede.ver.rutas_tareas'])->syncRoles([$jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.rutas_tareas'])->syncRoles([$jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.rutas_tareas'])->syncRoles([$jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.rutas_tareas'])->syncRoles([$jefe_tecnico]);
        // Rutas de tareas
        Permission::firstOrCreate(['name' => 'puede.ver.monitor_subtareas'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.monitor_subtareas'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.monitor_subtareas'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.monitor_subtareas'])->syncRoles([$coordinador, $jefe_tecnico]);

        /**
         * Permisos  para fondo rotativo
         */

        //Gasto
        Permission::firstOrCreate(['name' => 'puede.ver.gasto'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => 'puede.crear.gasto'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => 'puede.elegir.autorizador.gasto'])->syncRoles([$empleado]);
        //Gasto coordinadores
        Permission::firstOrCreate(['name' => 'puede.ver.gasto_coordinador'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.gasto_coordinador'])->syncRoles([$coordinador, $jefe_tecnico]);
        //Motivo gasto
        Permission::firstOrCreate(['name' => 'puede.ver.motivo_gasto'])->syncRoles([$administrador_fondos]);
        Permission::firstOrCreate(['name' => 'puede.crear.motivo_gasto'])->syncRoles([$administrador_fondos]);
        Permission::firstOrCreate(['name' => 'puede.editar.motivo_gasto'])->syncRoles([$administrador_fondos]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.motivo_gasto'])->syncRoles([$administrador_fondos]);
        //detalle fondo
        Permission::firstOrCreate(['name' => 'puede.ver.detalle_fondo'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => 'puede.crear.detalle_fondo'])->syncRoles([$administrador_fondos]);
        Permission::firstOrCreate(['name' => 'puede.editar.detalle_fondo'])->syncRoles([$administrador_fondos]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.detalle_fondo'])->syncRoles([$administrador_fondos]);
        //subdetalle fondo
        Permission::firstOrCreate(['name' => 'puede.ver.sub_detalle_fondo'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => 'puede.crear.sub_detalle_fondo'])->syncRoles([$administrador_fondos]);
        Permission::firstOrCreate(['name' => 'puede.editar.sub_detalle_fondo'])->syncRoles([$administrador_fondos]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.sub_detalle_fondo'])->syncRoles([$administrador_fondos]);
        //Saldo
        Permission::firstOrCreate(['name' => 'puede.ver.saldo'])->syncRoles([$bodega, $coordinador, $coordinador_backup, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.saldo'])->syncRoles([$bodega, $coordinador, $coordinador_backup, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.saldo'])->syncRoles([$bodega, $coordinador, $coordinador_backup, $tecnico_lider, $contabilidad]);
        //Acreditacion
        Permission::firstOrCreate(['name' => 'puede.ver.acreditacion'])->syncRoles([$administrador_fondos, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.acreditacion'])->syncRoles([$administrador_fondos, $contabilidad]);
        //Transferencias
        Permission::firstOrCreate(['name' => 'puede.ver.transferencia'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => 'puede.crear.transferencia'])->syncRoles([$empleado]);
        //Reporte fondo
        Permission::firstOrCreate(['name' => 'puede.ver.reporte_fondo_fecha'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => 'puede.crear.reporte_fondo_fecha'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => 'puede.editar.reporte_fondo_fecha'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.reporte_fondo_fecha'])->syncRoles([$empleado]);
        //Reporte de Saldos
        Permission::firstOrCreate(['name' => 'puede.ver.reporte_saldo_actual'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => 'puede.crear.reporte_saldo_actual'])->syncRoles([$bodega, $coordinador, $coordinador_backup, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.reporte_saldo_actual'])->syncRoles([$bodega, $coordinador, $coordinador_backup, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.reporte_saldo_actual'])->syncRoles([$bodega, $coordinador, $coordinador_backup, $tecnico_lider, $contabilidad]);
        //Reporte Autorizacion
        Permission::firstOrCreate(['name' => 'puede.ver.reporte_autorizaciones'])->syncRoles([$contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.reporte_autorizaciones'])->syncRoles([$contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.reporte_autorizaciones'])->syncRoles([$contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.reporte_autorizaciones'])->syncRoles([$contabilidad]);
        //Reporte Consolidado
        Permission::firstOrCreate(['name' => 'puede.ver.reporte_consolidado'])->syncRoles([$contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.reporte_consolidado'])->syncRoles([$contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.reporte_consolidado'])->syncRoles([$contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.reporte_consolidado'])->syncRoles([$contabilidad]);
        //Reporte Consolidado Filtrado
        Permission::firstOrCreate(['name' => 'puede.ver.reporte_consolidado_filtrado'])->syncRoles([$contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.reporte_consolidado_filtrado'])->syncRoles([$contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.reporte_consolidado_filtrado'])->syncRoles([$contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.reporte_consolidado_filtrado'])->syncRoles([$contabilidad]);

        //Reporte Contabilidad
        Permission::firstOrCreate(['name' => 'puede.ver.reporte_contabilidad'])->syncRoles([$contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.reporte_contabilidad'])->syncRoles([$contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.reporte_contabilidad'])->syncRoles([$contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.reporte_contabilidad'])->syncRoles([$contabilidad]);

        //Reporte Solicitud Fondo
        Permission::firstOrCreate(['name' => 'puede.ver.reporte_solicitud_fondo'])->syncRoles([$contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.reporte_solicitud_fondo'])->syncRoles([$contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.reporte_solicitud_fondo'])->syncRoles([$contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.reporte_solicitud_fondo'])->syncRoles([$contabilidad]);
        //Buscar saldo usuarios
        Permission::firstOrCreate(['name' => 'puede.buscar.saldo.usuarios'])->syncRoles([$contabilidad, $coordinador]);
        //Menus
        Permission::firstOrCreate(['name' => 'puede.ver.menu.detalle_fondo'])->syncRoles([$administrador_fondos]);
        Permission::firstOrCreate(['name' => 'puede.ver.menu.saldos'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => 'puede.ver.fondo'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => 'puede.ver.permisos'])->syncRoles([$administrador]);
        Permission::firstOrCreate(['name' => 'puede.ver.menu.solicitud_fondo'])->syncRoles([$administrador_fondos]);
        /**Fin de permisos para Fondos Rotativos */

        //Cambiar Contraseña
        Permission::firstOrCreate(['name' => 'puede.ver.cambiar_contrasena'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => 'puede.crear.cambiar_contrasena'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => 'puede.editar.cambiar_contrasena'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.cambiar_contrasena'])->syncRoles([$empleado]);

        // Clientes finales
        Permission::firstOrCreate(['name' => 'puede.ver.reporte_trabajos_realizados'])->syncRoles([$coordinador, $coordinador_backup, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.reporte_trabajos_realizados'])->syncRoles([$coordinador, $coordinador_backup, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.reporte_trabajos_realizados'])->syncRoles([$coordinador, $coordinador_backup, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.reporte_trabajos_realizados'])->syncRoles([$coordinador, $coordinador_backup, $jefe_tecnico]);
        // Hoja
        Permission::firstOrCreate(['name' => 'puede.ver.hoja_control_trabajos'])->syncRoles([$coordinador, $coordinador_backup, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.hoja_control_trabajos'])->syncRoles([$coordinador, $coordinador_backup, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.hoja_control_trabajos'])->syncRoles([$coordinador, $coordinador_backup, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.hoja_control_trabajos'])->syncRoles([$coordinador, $coordinador_backup, $jefe_tecnico]);
        // Motivos de pausas
        Permission::firstOrCreate(['name' => 'puede.ver.motivos_pausas'])->syncRoles([$jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.motivos_pausas'])->syncRoles([$jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.motivos_pausas'])->syncRoles([$jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.motivos_pausas'])->syncRoles([$jefe_tecnico]);
        // Motivos de pendientes
        Permission::firstOrCreate(['name' => 'puede.ver.motivos_pendientes'])->syncRoles([$jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.motivos_pendientes'])->syncRoles([$jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.motivos_pendientes'])->syncRoles([$jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.motivos_pendientes'])->syncRoles([$jefe_tecnico]);
        // Motivos de suspendidos
        Permission::firstOrCreate(['name' => 'puede.ver.motivos_suspendidos'])->syncRoles([$jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.motivos_suspendidos'])->syncRoles([$jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.motivos_suspendidos'])->syncRoles([$jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.motivos_suspendidos'])->syncRoles([$jefe_tecnico]);
    }
}
