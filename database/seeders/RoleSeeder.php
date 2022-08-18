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
        Role::create(['name' => User::ROL_ADMINISTRADOR]);
        $rol_coordinador = Role::create(['name' => User::ROL_COORDINADOR]);
        $rol_bodega = Role::create(['name' => User::ROL_BODEGA]);
        $rol_empleado = Role::create(['name' => User::ROL_EMPLEADO]);
        $rol_jefe_tecnico = Role::create(['name' => User::ROL_JEFE_TECNICO]);
        $rol_gerente = Role::create(['name' => User::ROL_GERENTE]);
        $rol_compras = Role::create(['name' => User::ROL_COMPRAS]);
        $rol_tecnico = Role::create(['name' => User::ROL_TECNICO]);
        $rol_activos_fijos = Role::create(['name' => User::ROL_ACTIVOS_FIJOS]);


        Permission::create(['name' => 'puede.ver.categorias'])->syncRoles([$rol_activos_fijos, $rol_empleado]);
        Permission::create(['name' => 'puede.crear.categorias'])->assignRole($rol_activos_fijos);
        Permission::create(['name' => 'puede.editar.categorias'])->assignRole($rol_activos_fijos);

        Permission::create(['name' => 'puede.ver.nombres_de_productos'])->syncRoles($rol_activos_fijos, $rol_empleado);
        Permission::create(['name' => 'puede.crear.nombres_de_productos'])->assignRole($rol_activos_fijos);
        Permission::create(['name' => 'puede.editar.nombres_de_productos'])->assignRole($rol_activos_fijos);

        Permission::create(['name' => 'puede.ver.productos'])->assignRole($rol_empleado);
        Permission::create(['name' => 'puede.crear.productos'])->assignRole($rol_bodega);
        Permission::create(['name' => 'puede.editar.productos'])->assignRole($rol_bodega);
        Permission::create(['name' => 'puede.eliminar.productos']);


        Permission::create(['name' => 'puede.solicitar.productos'])->syncRoles([$rol_bodega, $rol_empleado]);
        Permission::create(['name' => 'puede.ver.nombres.de.productos'])->syncRoles([$rol_bodega, $rol_empleado]);

        Permission::create(['name' => 'puede.ver.materiales'])->syncRoles([$rol_coordinador, $rol_bodega]);
        Permission::create(['name' => 'puede.crear.liquidacion'])->syncRoles([$rol_coordinador, $rol_bodega]);
        Permission::create(['name' => 'puede.crear.transaccion'])->syncRoles([$rol_coordinador, $rol_bodega]);
        Permission::create(['name' => 'puede.editar.transaccion'])->syncRoles([$rol_coordinador, $rol_bodega]);
        Permission::create(['name' => 'puede.autorizar.transaccion'])->syncRoles([$rol_coordinador, $rol_gerente, $rol_jefe_tecnico]);

        Permission::create(['name' => 'puede.crear.compras'])->assignRole($rol_compras);
    }
}
