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
        Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR]);
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

        // Roles de cuadrillas
        $tecnico = Role::firstOrCreate(['name' => User::ROL_TECNICO]);
        $tecnico_lider = Role::firstOrCreate(['name' => User::ROL_TECNICO_LIDER_DE_GRUPO]);
        $autorizador = Role::firstOrCreate(['name' => User::ROL_AUTORIZADOR]);

        // -----------------
        // Modulo de Sistema
        // -----------------
        // Tablero
        Permission::firstOrCreate(['name' => 'puede.ver.tablero'])->syncRoles([$coordinador, $contabilidad, $bodega, $empleado, $jefe_tecnico, $gerente, $compras, $tecnico,  $activos_fijos, $recursos_humanos]);
        // Perfil
        Permission::firstOrCreate(['name' => 'puede.ver.perfil'])->syncRoles([$coordinador, $bodega, $empleado, $jefe_tecnico, $gerente, $compras, $tecnico,  $activos_fijos]);
        // Administración
        Permission::firstOrCreate(['name' => 'puede.ver.modulo_administracion'])->syncRoles([$bodega, $activos_fijos]);
        // Configuracion
        Permission::firstOrCreate(['name' => 'puede.ver.configuracion'])->syncRoles([$coordinador, $bodega, $empleado, $jefe_tecnico, $gerente, $compras, $tecnico,  $activos_fijos]);


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
        Permission::firstOrCreate(['name' => 'puede.ver.modulo_bodega'])->syncRoles([$activos_fijos, $administrativo, $bodega, $coordinador, $tecnico,  $contabilidad, $empleado]);
        Permission::firstOrCreate(['name' => 'puede.ver.modulo_recursos_humanos'])->assignRole($recursos_humanos);
        // Autorizaciones
        Permission::firstOrCreate(['name' => 'puede.ver.autorizaciones'])->syncRoles([$activos_fijos, $bodega, $contabilidad, $coordinador, $administrativo, $empleado, $tecnico]);
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
        Permission::firstOrCreate(['name' => 'puede.ver.clientes'])->syncRoles([$jefe_tecnico, $activos_fijos, $bodega, $coordinador, $tecnico,  $empleado]);
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
        Permission::firstOrCreate(['name' => 'puede.ver.detalles'])->syncRoles([$empleado, $coordinador, $activos_fijos, $tecnico,  $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.detalles'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.detalles'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.eliminar.detalles'])->assignRole($bodega);

        // Devolución
        Permission::firstOrCreate(['name' => 'puede.ver.devoluciones'])->syncRoles([$empleado, $coordinador, $activos_fijos, $tecnico,  $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.devoluciones'])->syncRoles([$bodega, $empleado]);
        // Permission::firstOrCreate(['name' => 'puede.editar.devoluciones'])->syncRoles([$bodega, $empleado]);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.devoluciones'])->assignRole($bodega);

        // Empleados
        Permission::firstOrCreate(['name' => 'puede.ver.empleados'])->syncRoles([$recursos_humanos, $coordinador, $activos_fijos, $bodega, $empleado]);
        Permission::firstOrCreate(['name' => 'puede.crear.empleados'])->assignRole($recursos_humanos);
        Permission::firstOrCreate(['name' => 'puede.editar.empleados'])->assignRole($recursos_humanos);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.empleados'])->assignRole($recursos_humanos);

        // Empresas
        Permission::firstOrCreate(['name' => 'puede.ver.empresas'])->syncRoles([$jefe_tecnico, $empleado, $activos_fijos, $coordinador, $tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.empresas'])->syncRoles([$jefe_tecnico, $activos_fijos]);
        Permission::firstOrCreate(['name' => 'puede.editar.empresas'])->syncRoles([$jefe_tecnico, $activos_fijos]);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.empresas'])->assignRole($jefe_tecnico, $activos_fijos);

        //Estados de transacciones
        Permission::firstOrCreate(['name' => 'puede.ver.estados_transacciones'])->syncRoles([$activos_fijos, $bodega, $contabilidad, $coordinador, $administrativo, $empleado, $tecnico]);
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
        Permission::firstOrCreate(['name' => 'puede.ver.motivos'])->syncRoles([$jefe_tecnico, $activos_fijos, $coordinador, $bodega, $tecnico,  $contabilidad, $administrativo]);
        Permission::firstOrCreate(['name' => 'puede.crear.motivos'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.editar.motivos'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.eliminar.motivos'])->assignRole($activos_fijos);

        //Notificaciones
        Permission::firstOrCreate(['name' => 'puede.ver.notificaciones'])->assignRole($empleado);
        Permission::firstOrCreate(['name' => 'puede.crear.notificaciones'])->assignRole($empleado);
        Permission::firstOrCreate(['name' => 'puede.editar.notificaciones'])->assignRole($empleado);
        Permission::firstOrCreate(['name' => 'puede.eliminar.notificaciones'])->assignRole($empleado);

        //Pedidos
        Permission::firstOrCreate(['name' => 'puede.ver.pedidos'])->syncRoles([$empleado, $activos_fijos, $bodega, $tecnico,  $coordinador, $administrativo, $contabilidad, $jefe_tecnico]);
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
        Permission::firstOrCreate(['name' => 'puede.ver.productos'])->syncRoles([$activos_fijos, $bodega, $tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.productos'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.editar.productos'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.eliminar.productos'])->assignRole($activos_fijos);

        //Productos en perchas
        Permission::firstOrCreate(['name' => 'puede.ver.productos_perchas'])->syncRoles([$activos_fijos, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.crear.productos_perchas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.productos_perchas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.eliminar.productos_perchas'])->assignRole($bodega);

        //Proveedores
        Permission::firstOrCreate(['name' => 'puede.ver.proveedores'])->syncRoles([$jefe_tecnico, $activos_fijos, $coordinador, $bodega, $tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.proveedores'])->assignRole($compras);
        Permission::firstOrCreate(['name' => 'puede.editar.proveedores'])->assignRole($compras);
        Permission::firstOrCreate(['name' => 'puede.eliminar.proveedores'])->assignRole($compras);

        //Sucursales
        Permission::firstOrCreate(['name' => 'puede.ver.sucursales'])->syncRoles([$activos_fijos, $bodega, $recursos_humanos, $tecnico,  $contabilidad, $coordinador, $administrativo]);
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
        Permission::firstOrCreate(['name' => 'puede.ver.tipos_fibras'])->syncRoles([$coordinador, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.crear.tipos_fibras'])->syncRoles([$coordinador, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.editar.tipos_fibras'])->syncRoles([$coordinador, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.tipos_fibras'])->syncRoles([$coordinador, $bodega]);
        //Tipos de transacciones
        Permission::firstOrCreate(['name' => 'puede.ver.tipos_transacciones'])->syncRoles([$activos_fijos, $bodega, $tecnico,  $contabilidad, $coordinador, $administrativo]);
        Permission::firstOrCreate(['name' => 'puede.crear.tipos_transacciones'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.editar.tipos_transacciones'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.eliminar.tipos_transacciones'])->assignRole($activos_fijos);
        //Transacciones
        Permission::firstOrCreate(['name' => 'puede.ver.transacciones_egresos'])->syncRoles([$bodega, $contabilidad, $coordinador]);
        Permission::firstOrCreate(['name' => 'puede.crear.transacciones_egresos'])->syncRoles([$bodega]);
        Permission::firstOrCreate(['name' => 'puede.editar.transacciones_egresos'])->syncRoles([$bodega, $coordinador]);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.transacciones_egresos'])->syncRoles([$bodega, $tecnico]);

        Permission::firstOrCreate(['name' => 'puede.ver.transacciones_ingresos'])->syncRoles([$bodega, $contabilidad, $coordinador]);
        Permission::firstOrCreate(['name' => 'puede.crear.transacciones_ingresos'])->syncRoles([$bodega]);
        Permission::firstOrCreate(['name' => 'puede.editar.transacciones_ingresos'])->syncRoles([$bodega, $coordinador]);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.transacciones_ingresos'])->syncRoles([$bodega]);

        Permission::firstOrCreate(['name' => 'puede.ver.transacciones'])->syncRoles([$bodega, $coordinador, $tecnico,  $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.transacciones'])->syncRoles([$bodega, $coordinador, $tecnico,  $administrativo, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.transacciones'])->syncRoles([$bodega, $tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.transacciones']); //->syncRoles([$coordinador, $gerente, $jefe_tecnico]);
        // Permission::firstOrCreate(['name' => 'puede.autorizar.transacciones'])->syncRoles([$coordinador, $gerente, $jefe_tecnico]);

        //Ubicaciones
        Permission::firstOrCreate(['name' => 'puede.ver.ubicaciones'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.crear.ubicaciones'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.ubicaciones'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.eliminar.ubicaciones'])->assignRole($bodega);

        //Ubicaciones
        Permission::firstOrCreate(['name' => 'puede.ver.unidades_medidas'])->syncRoles([$bodega, $coordinador, $tecnico,  $contabilidad, $activos_fijos]);
        Permission::firstOrCreate(['name' => 'puede.crear.unidades_medidas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.unidades_medidas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.eliminar.unidades_medidas'])->assignRole($bodega);




        Permission::firstOrCreate(['name' => 'puede.ver.materiales'])->syncRoles([$coordinador, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.crear.liquidacion'])->syncRoles([$coordinador, $bodega]);

        Permission::firstOrCreate(['name' => 'puede.crear.compras'])->assignRole($compras);


        // -----------------
        // Modulo de Tareas
        // -----------------
        Permission::firstOrCreate(['name' => 'puede.ver.modulo_tareas'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.ver.trabajo_asignado'])->syncRoles([$tecnico,  $coordinador, $administrativo]);
        Permission::firstOrCreate(['name' => 'puede.ver.subtarea_asignada'])->assignRole($tecnico);
        Permission::firstOrCreate(['name' => 'puede.ver.control_avance'])->syncRoles([$tecnico,  $coordinador]);
        Permission::firstOrCreate(['name' => 'puede.ver.gestionar_avances'])->syncRoles([$tecnico, $coordinador]);
        Permission::firstOrCreate(['name' => 'puede.ver.tablero_personal'])->syncRoles([$coordinador, $bodega, $empleado, $jefe_tecnico, $gerente, $compras, $tecnico,  $activos_fijos, $administrativo, $recursos_humanos]);

        // Proyectos
        /**
         * El jefe tecnico y los coordinadores son los encargados de crear proyectos.
         * Dentro de cada proyecto van las tareas y dentro de las mismas van las subtareas.
         */
        Permission::firstOrCreate(['name' => 'puede.ver.proyectos'])->syncRoles([$jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.proyectos'])->syncRoles([$jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.proyectos'])->syncRoles([$jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.proyectos'])->syncRoles([$jefe_tecnico]);
        // Tareas
        /**
         * Los coordinadores son los encargados de crear las tareas
         */
        Permission::firstOrCreate(['name' => 'puede.ver.tareas'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.tareas'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.tareas'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.tareas'])->syncRoles([$coordinador, $jefe_tecnico]);
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
         * Los coordinadores son los encargados de crear los tipos de trabajos que ellos crean convenientes
         */
        Permission::firstOrCreate(['name' => 'puede.ver.tipos_trabajos'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.crear.tipos_trabajos'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.editar.tipos_trabajos'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.eliminar.tipos_trabajos'])->assignRole($coordinador);
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
        Permission::firstOrCreate(['name' => 'puede.ver.control_asistencia'])->syncRoles([$coordinador, $tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.control_asistencia'])->syncRoles([$tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.control_asistencia'])->syncRoles([$tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.control_asistencia'])->syncRoles([$tecnico]);
        // Control de progresivas
        Permission::firstOrCreate(['name' => 'puede.ver.control_tendidos'])->syncRoles([$coordinador, $tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.control_tendidos'])->syncRoles([$tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.control_tendidos'])->syncRoles([$coordinador, $tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.control_tendidos'])->syncRoles([$tecnico]);
        // Control de cambios
        Permission::firstOrCreate(['name' => 'puede.ver.control_cambios'])->syncRoles([$coordinador, $tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.control_cambios'])->syncRoles([$coordinador, $tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.control_cambios'])->syncRoles([$coordinador, $tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.control_cambios'])->syncRoles([$coordinador, $tecnico]);
        // Solicitud de materiales a bodega
        Permission::firstOrCreate(['name' => 'puede.ver.solicitud_materiales'])->syncRoles([$coordinador, $tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.solicitud_materiales'])->syncRoles([$coordinador, $tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.solicitud_materiales'])->syncRoles([$coordinador, $tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.solicitud_materiales'])->syncRoles([$coordinador, $tecnico]);
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
        Permission::firstOrCreate(['name' => 'puede.ver.clientes_finales'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.clientes_finales'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.clientes_finales'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.clientes_finales'])->syncRoles([$coordinador, $jefe_tecnico]);

        /**
         * Roles  para fondo rotativo
         */
        // fondo
        Permission::firstOrCreate(['name' => 'puede.ver.fondo'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.fondo'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.fondo'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.fondo'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        //Gasto
        Permission::firstOrCreate(['name' => 'puede.ver.gasto'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.gasto'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.gasto'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.gasto'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        //detalle fondo
        Permission::firstOrCreate(['name' => 'puede.ver.detalle_fondo'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.detalle_fondo'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.detalle_fondo'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.detalle_fondo'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        //subdetalle fondo
        Permission::firstOrCreate(['name' => 'puede.ver.sub_detalle_fondo'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.sub_detalle_fondo'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.sub_detalle_fondo'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.sub_detalle_fondo'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        //Saldo
        Permission::firstOrCreate(['name' => 'puede.ver.saldo'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.saldo'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.saldo'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.saldo'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        //Acreditacion
        Permission::firstOrCreate(['name' => 'puede.ver.acreditacion'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.acreditacion'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.acreditacion'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.acreditacion'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        //Reporte fondo
        Permission::firstOrCreate(['name' => 'puede.ver.reporte_fondo_fecha'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.reporte_fondo_fecha'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.reporte_fondo_fecha'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.reporte_fondo_fecha'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        //Reporte de Saldos
        Permission::firstOrCreate(['name' => 'puede.ver.reporte_saldo_actual'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.reporte_saldo_actual'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.reporte_saldo_actual'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.reporte_saldo_actual'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        //Reporte Autorizacion
        Permission::firstOrCreate(['name' => 'puede.ver.reporte_autorizaciones'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.reporte_autorizaciones'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.reporte_autorizaciones'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.reporte_autorizaciones'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        //Reporte Consolidado
        Permission::firstOrCreate(['name' => 'puede.ver.reporte_consolidado'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.crear.reporte_consolidado'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.editar.reporte_consolidado'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.reporte_consolidado'])->syncRoles([$bodega, $coordinador, $tecnico_lider, $contabilidad]);



        // Clientes finales
        Permission::firstOrCreate(['name' => 'puede.ver.reporte_trabajos_realizados'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.reporte_trabajos_realizados'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.reporte_trabajos_realizados'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.reporte_trabajos_realizados'])->syncRoles([$coordinador, $jefe_tecnico]);
        // Hoja
        Permission::firstOrCreate(['name' => 'puede.ver.hoja_control_trabajos'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.crear.hoja_control_trabajos'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.editar.hoja_control_trabajos'])->syncRoles([$coordinador, $jefe_tecnico]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.hoja_control_trabajos'])->syncRoles([$coordinador, $jefe_tecnico]);
    }
}
