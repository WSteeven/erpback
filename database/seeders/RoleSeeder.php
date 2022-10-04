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
        $coordinador = Role::firstOrCreate(['name' => User::ROL_COORDINADOR]);
        $bodega = Role::firstOrCreate(['name' => User::ROL_BODEGA]);
        $empleado = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);
        $jefe_tecnico = Role::firstOrCreate(['name' => User::ROL_JEFE_TECNICO]);
        $gerente = Role::firstOrCreate(['name' => User::ROL_GERENTE]);
        $compras = Role::firstOrCreate(['name' => User::ROL_COMPRAS]);
        $tecnico_lider = Role::firstOrCreate(['name' => User::ROL_TECNICO_LIDER]);
        $tecnico = Role::firstOrCreate(['name' => User::ROL_TECNICO]);
        $activos_fijos = Role::firstOrCreate(['name' => User::ROL_ACTIVOS_FIJOS]);
        $administrativo = Role::firstOrCreate(['name' => User::ROL_ADMINISTRATIVO]);
        $recursos_humanos = Role::firstOrCreate(['name' => User::ROL_RECURSOS_HUMANOS]);

        // -----------------
        // Modulo de Sistema
        // -----------------
        // Tablero
        Permission::firstOrCreate(['name' => 'puede.ver.tablero'])->syncRoles([$coordinador, $bodega, $empleado, $jefe_tecnico, $gerente, $compras, $tecnico, $activos_fijos, $administrativo, $recursos_humanos]);
        // Perfil
        Permission::firstOrCreate(['name' => 'puede.ver.perfil'])->syncRoles([$coordinador, $bodega, $empleado, $jefe_tecnico, $gerente, $compras, $tecnico, $activos_fijos]);
        // Configuracion
        Permission::firstOrCreate(['name' => 'puede.ver.configuracion'])->syncRoles([$coordinador, $bodega, $empleado, $jefe_tecnico, $gerente, $compras, $tecnico, $activos_fijos]);


        // -----------------
        // Modulo de Bodega
        // -----------------
        Permission::firstOrCreate(['name' => 'puede.ver.modulo_bodega'])->syncRoles([$activos_fijos, $administrativo, $bodega, $coordinador]);
        Permission::firstOrCreate(['name' => 'puede.ver.modulo_recursos_humanos'])->assignRole($recursos_humanos);
        // Autorizaciones
        Permission::firstOrCreate(['name' => 'puede.ver.autorizaciones'])->syncRoles([$activos_fijos, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.crear.autorizaciones'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.editar.autorizaciones'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.eliminar.autorizaciones'])->assignRole($activos_fijos);

        //Categorias
        Permission::firstOrCreate(['name' => 'puede.ver.categorias'])->syncRoles([$activos_fijos, $bodega, $coordinador]);
        Permission::firstOrCreate(['name' => 'puede.crear.categorias'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.editar.categorias'])->assignRole($activos_fijos);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.categorias'])->assignRole($activos_fijos);

        //Clientes
        Permission::firstOrCreate(['name' => 'puede.ver.clientes'])->syncRoles([$jefe_tecnico, $activos_fijos, $bodega, $coordinador]);
        Permission::firstOrCreate(['name' => 'puede.crear.clientes'])->assignRole($jefe_tecnico);
        Permission::firstOrCreate(['name' => 'puede.editar.clientes'])->assignRole($jefe_tecnico);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.clientes'])->assignRole($jefe_tecnico);
        
        //Codigos de clientes
        Permission::firstOrCreate(['name' => 'puede.ver.codigos_clientes'])->syncRoles([$activos_fijos, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.crear.codigos_clientes'])->assignRole($jefe_tecnico);
        Permission::firstOrCreate(['name' => 'puede.editar.codigos_clientes'])->assignRole($jefe_tecnico);
        // Permission::firstOrCreate(['name' => 'puede.eliminar.codigos_clientes'])->assignRole($jefe_tecnico);

        //Condiciones
        Permission::firstOrCreate(['name' => 'puede.ver.condiciones'])->syncRoles([$activos_fijos, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.crear.condiciones'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.editar.condiciones'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.eliminar.condiciones'])->assignRole($activos_fijos);
        
        // Detalles de productos
        Permission::firstOrCreate(['name' => 'puede.ver.detalles'])->syncRoles([$empleado, $activos_fijos]);
        Permission::firstOrCreate(['name' => 'puede.crear.detalles'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.detalles'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.eliminar.detalles'])->assignRole($bodega);
        
        // Detalles de productos
        Permission::firstOrCreate(['name' => 'puede.ver.detalles'])->syncRoles([$empleado, $activos_fijos]);
        Permission::firstOrCreate(['name' => 'puede.crear.detalles'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.detalles'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.eliminar.detalles'])->assignRole($bodega);
        
        // Empleados
        Permission::firstOrCreate(['name' => 'puede.ver.empleados'])->assignRole($recursos_humanos);
        Permission::firstOrCreate(['name' => 'puede.crear.empleados'])->assignRole($recursos_humanos);
        Permission::firstOrCreate(['name' => 'puede.editar.empleados'])->assignRole($recursos_humanos);
        Permission::firstOrCreate(['name' => 'puede.eliminar.empleados'])->assignRole($recursos_humanos);

        //Estados de transacciones
        Permission::firstOrCreate(['name' => 'puede.ver.estados']);
        // Hilos
        Permission::firstOrCreate(['name' => 'puede.ver.hilos']);
        //Marcas
        Permission::firstOrCreate(['name' => 'puede.ver.marcas'])->syncRoles($activos_fijos, $bodega);
        Permission::firstOrCreate(['name' => 'puede.crear.marcas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.marcas'])->assignRole($bodega);
        //Modelos
        Permission::firstOrCreate(['name' => 'puede.ver.modelos'])->syncRoles($activos_fijos, $bodega);
        Permission::firstOrCreate(['name' => 'puede.crear.modelos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => 'puede.editar.modelos'])->assignRole($bodega);
        //Productos
        Permission::firstOrCreate(['name' => 'puede.ver.productos'])->syncRoles([$activos_fijos, $bodega, $tecnico_lider]);
        Permission::firstOrCreate(['name' => 'puede.crear.productos'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.editar.productos'])->assignRole($activos_fijos);
        Permission::firstOrCreate(['name' => 'puede.eliminar.productos'])->assignRole($activos_fijos);
        
        //Inventarios
        Permission::firstOrCreate(['name' => 'puede.ver.inventarios']);
        Permission::firstOrCreate(['name' => 'puede.crear.inventarios']);
        Permission::firstOrCreate(['name' => 'puede.editar.inventarios']);
        Permission::firstOrCreate(['name' => 'puede.eliminar.inventarios']);

        Permission::firstOrCreate(['name' => 'puede.ver.materiales'])->syncRoles([$coordinador, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.crear.liquidacion'])->syncRoles([$coordinador, $bodega]);
        //Tipos de fibras
        Permission::firstOrCreate(['name' => 'puede.ver.tipos_fibras'])->syncRoles([$coordinador, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.crear.tipos_fibras'])->syncRoles([$coordinador, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.editar.tipos_fibras'])->syncRoles([$coordinador, $bodega]);
        Permission::firstOrCreate(['name' => 'puede.eliminar.tipos_fibras'])->syncRoles([$coordinador, $bodega]);
        //Tipos de transacciones
        Permission::firstOrCreate(['name' => 'puede.ver.tipos_transacciones']);
        //Subtipos de transacciones
        Permission::firstOrCreate(['name' => 'puede.ver.subtipos_transacciones']);
        //Transacciones
        Permission::firstOrCreate(['name' => 'puede.ver.transacciones_egresos'])->syncRoles([$bodega, $tecnico, $administrativo, $coordinador]);
        Permission::firstOrCreate(['name' => 'puede.ver.transacciones_ingresos'])->syncRoles([$bodega, $coordinador]);
        Permission::firstOrCreate(['name' => 'puede.ver.transaccion'])->syncRoles([$bodega]);
        Permission::firstOrCreate(['name' => 'puede.crear.transacciones'])->syncRoles([$bodega, $coordinador, $tecnico, $administrativo]);
        Permission::firstOrCreate(['name' => 'puede.editar.transaccion'])->syncRoles([$bodega]);
        Permission::firstOrCreate(['name' => 'puede.autorizar.transaccion'])->syncRoles([$coordinador, $gerente, $jefe_tecnico]);

        Permission::firstOrCreate(['name' => 'puede.crear.compras'])->assignRole($compras);
        //Sucursales
        Permission::firstOrCreate(['name' => 'puede.ver.perchas']);
        //Sucursales
        Permission::firstOrCreate(['name' => 'puede.ver.pisos']);
        //Sucursales
        Permission::firstOrCreate(['name' => 'puede.ver.sucursales']);
        Permission::firstOrCreate(['name' => 'puede.crear.sucursales']);
        Permission::firstOrCreate(['name' => 'puede.editar.sucursales']);
        //Sucursales
        Permission::firstOrCreate(['name' => 'puede.ver.ubicaciones']);


        // -----------------
        // Modulo de Tareas
        // -----------------
        Permission::firstOrCreate(['name' => 'puede.ver.modulo_tareas'])->assignRole($coordinador);

        // Tareas
        Permission::firstOrCreate(['name' => 'puede.ver.tareas'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.crear.tareas'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.editar.tareas'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.eliminar.tareas'])->assignRole($coordinador);
        // Subtareas
        Permission::firstOrCreate(['name' => 'puede.ver.subtareas'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.crear.subtareas'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.editar.subtareas'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.eliminar.subtareas'])->assignRole($coordinador);
        // Tipos tareas
        Permission::firstOrCreate(['name' => 'puede.ver.tipos_tareas'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.crear.tipos_tareas'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.editar.tipos_tareas'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.eliminar.tipos_tareas'])->assignRole($coordinador);
        // Progresivas
        Permission::firstOrCreate(['name' => 'puede.ver.progresivas'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.crear.progresivas'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.editar.progresivas'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.eliminar.progresivas'])->assignRole($coordinador);
        // Tipos elementos
        Permission::firstOrCreate(['name' => 'puede.ver.tipos_elementos'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.crear.tipos_elementos'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.editar.tipos_elementos'])->assignRole($coordinador);
        Permission::firstOrCreate(['name' => 'puede.eliminar.tipos_elementos'])->assignRole($coordinador);
        // Control de asistencia
        Permission::firstOrCreate(['name' => 'puede.ver.control_asistencia'])->assignRole($coordinador, $tecnico);
        Permission::firstOrCreate(['name' => 'puede.crear.control_asistencia'])->assignRole($tecnico);
        Permission::firstOrCreate(['name' => 'puede.editar.control_asistencia'])->assignRole($tecnico);
        Permission::firstOrCreate(['name' => 'puede.eliminar.control_asistencia'])->assignRole($tecnico);
        // Control de progresivas
        Permission::firstOrCreate(['name' => 'puede.ver.control_progresivas'])->assignRole($coordinador, $tecnico);
        Permission::firstOrCreate(['name' => 'puede.crear.control_progresivas'])->assignRole($tecnico);
        Permission::firstOrCreate(['name' => 'puede.editar.control_progresivas'])->assignRole($coordinador, $tecnico);
        Permission::firstOrCreate(['name' => 'puede.eliminar.control_progresivas'])->assignRole($tecnico);
        // Control de cambios
        Permission::firstOrCreate(['name' => 'puede.ver.control_cambios'])->assignRole($coordinador, $tecnico);
        Permission::firstOrCreate(['name' => 'puede.crear.control_cambios'])->assignRole($coordinador, $tecnico);
        Permission::firstOrCreate(['name' => 'puede.editar.control_cambios'])->assignRole($coordinador, $tecnico);
        Permission::firstOrCreate(['name' => 'puede.eliminar.control_cambios'])->assignRole($coordinador, $tecnico);
    }
}
