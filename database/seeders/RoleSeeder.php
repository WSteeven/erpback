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
        Role::create(['name' => User::ROL_ADMINISTRADOR]);
        $coordinador = Role::create(['name' => User::ROL_COORDINADOR]);
        $bodega = Role::create(['name' => User::ROL_BODEGA]);
        $empleado = Role::create(['name' => User::ROL_EMPLEADO]);
        $jefe_tecnico = Role::create(['name' => User::ROL_JEFE_TECNICO]);
        $gerente = Role::create(['name' => User::ROL_GERENTE]);
        $compras = Role::create(['name' => User::ROL_COMPRAS]);
        $tecnico = Role::create(['name' => User::ROL_TECNICO]);
        $activos_fijos = Role::create(['name' => User::ROL_ACTIVOS_FIJOS]);

        // -----------------
        // Modulo de Sistema
        // -----------------
        // Tablero
        Permission::create(['name' => 'puede.ver.tablero'])->syncRoles([$coordinador, $bodega, $empleado, $jefe_tecnico, $gerente, $compras, $tecnico, $activos_fijos]);
        // Perfil
        Permission::create(['name' => 'puede.ver.perfil'])->syncRoles([$coordinador, $bodega, $empleado, $jefe_tecnico, $gerente, $compras, $tecnico, $activos_fijos]);
        // Configuracion
        Permission::create(['name' => 'puede.ver.configuracion'])->syncRoles([$coordinador, $bodega, $empleado, $jefe_tecnico, $gerente, $compras, $tecnico, $activos_fijos]);


        // -----------------
        // Modulo de Bodega
        // -----------------
        Permission::create(['name' => 'puede.ver.modulo_bodega'])->assignRole($activos_fijos, $bodega, $empleado);

        Permission::create(['name' => 'puede.ver.categorias'])->syncRoles([$activos_fijos, $empleado]);
        Permission::create(['name' => 'puede.crear.categorias'])->assignRole($activos_fijos);
        Permission::create(['name' => 'puede.editar.categorias'])->assignRole($activos_fijos);

        Permission::create(['name' => 'puede.ver.nombres_de_productos'])->syncRoles($activos_fijos, $empleado);
        Permission::create(['name' => 'puede.crear.nombres_de_productos'])->assignRole($activos_fijos);
        Permission::create(['name' => 'puede.editar.nombres_de_productos'])->assignRole($activos_fijos);

        Permission::create(['name' => 'puede.ver.productos'])->assignRole($empleado);
        Permission::create(['name' => 'puede.crear.productos'])->assignRole($bodega);
        Permission::create(['name' => 'puede.editar.productos'])->assignRole($bodega);
        Permission::create(['name' => 'puede.eliminar.productos']);


        Permission::create(['name' => 'puede.solicitar.productos'])->syncRoles([$bodega, $empleado]);
        Permission::create(['name' => 'puede.ver.nombres.de.productos'])->syncRoles([$bodega, $empleado]);

        Permission::create(['name' => 'puede.ver.materiales'])->syncRoles([$coordinador, $bodega]);
        Permission::create(['name' => 'puede.crear.liquidacion'])->syncRoles([$coordinador, $bodega]);

        Permission::create(['name' => 'puede.ver.transaccion'])->syncRoles([$bodega]);
        Permission::create(['name' => 'puede.crear.transaccion'])->syncRoles([$empleado]);
        Permission::create(['name' => 'puede.editar.transaccion'])->syncRoles([$bodega]);
        Permission::create(['name' => 'puede.autorizar.transaccion'])->syncRoles([$coordinador, $gerente, $jefe_tecnico]);

        Permission::create(['name' => 'puede.crear.compras'])->assignRole($compras);

        // -----------------
        // Modulo de Tareas
        // -----------------
        Permission::create(['name' => 'puede.ver.modulo_tareas'])->assignRole($coordinador);

        // Tareas
        Permission::create(['name' => 'puede.ver.tareas'])->assignRole($coordinador);
        Permission::create(['name' => 'puede.crear.tareas'])->assignRole($coordinador);
        Permission::create(['name' => 'puede.editar.tareas'])->assignRole($coordinador);
        Permission::create(['name' => 'puede.eliminar.tareas'])->assignRole($coordinador);
        // Subtareas
        Permission::create(['name' => 'puede.ver.subtareas'])->assignRole($coordinador);
        Permission::create(['name' => 'puede.crear.subtareas'])->assignRole($coordinador);
        Permission::create(['name' => 'puede.editar.subtareas'])->assignRole($coordinador);
        Permission::create(['name' => 'puede.eliminar.subtareas'])->assignRole($coordinador);
        // Tipos tareas
        Permission::create(['name' => 'puede.ver.tipos_tareas'])->assignRole($coordinador);
        Permission::create(['name' => 'puede.crear.tipos_tareas'])->assignRole($coordinador);
        Permission::create(['name' => 'puede.editar.tipos_tareas'])->assignRole($coordinador);
        Permission::create(['name' => 'puede.eliminar.tipos_tareas'])->assignRole($coordinador);
        // Progresivas
        Permission::create(['name' => 'puede.ver.progresivas'])->assignRole($coordinador);
        Permission::create(['name' => 'puede.crear.progresivas'])->assignRole($coordinador);
        Permission::create(['name' => 'puede.editar.progresivas'])->assignRole($coordinador);
        Permission::create(['name' => 'puede.eliminar.progresivas'])->assignRole($coordinador);
        // Tipos elementos
        Permission::create(['name' => 'puede.ver.tipos_elementos'])->assignRole($coordinador);
        Permission::create(['name' => 'puede.crear.tipos_elementos'])->assignRole($coordinador);
        Permission::create(['name' => 'puede.editar.tipos_elementos'])->assignRole($coordinador);
        Permission::create(['name' => 'puede.eliminar.tipos_elementos'])->assignRole($coordinador);
    }
}
