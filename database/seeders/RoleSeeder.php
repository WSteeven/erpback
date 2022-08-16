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
        Role::create(['name'=>User::ROL_ADMINISTRADOR]);
        $rol_coordinador= Role::create(['name'=>User::ROL_COORDINADOR]);
        $rol_bodega= Role::create(['name'=>User::ROL_BODEGA]);
        $rol_empleado= Role::create(['name'=>User::ROL_EMPLEADO]);
        $rol_jefe_tecnico= Role::create(['name'=>User::ROL_JEFE_TECNICO]);
        $rol_gerente= Role::create(['name'=>User::ROL_GERENTE]);
        $rol_compras= Role::create(['name'=>User::ROL_COMPRAS]);
        $rol_tecnico= Role::create(['name'=>User::ROL_TECNICO]);

        /* Permission::create(['name'=>'puede.ver.categorias'])->assignRole($rol_bodega);
        Permission::create(['name'=>'puede.ver.productos'])->syncRoles([$rol_bodega, $rol_empleado]);
        Permission::create(['name'=>'puede.crear.productos'])->syncRoles([$rol_bodega, $rol_empleado]);
        Permission::create(['name'=>'puede.solicitar.productos'])->syncRoles([$rol_bodega, $rol_empleado]);
        Permission::create(['name'=>'puede.ver.nombres.de.productos'])->syncRoles([$rol_bodega, $rol_empleado]);

        Permission::create(['name'=>'puede.ver.materiales'])->syncRoles([$rol_coordinador, $rol_bodega]);
        Permission::create(['name'=>'puede.crear.liquidacion'])->syncRoles([$rol_coordinador, $rol_bodega]);
        Permission::create(['name'=>'puede.crear.transaccion'])->syncRoles([$rol_coordinador, $rol_bodega]);
        Permission::create(['name'=>'puede.editar.transaccion'])->syncRoles([$rol_coordinador, $rol_bodega]);
        Permission::create(['name'=>'puede.autorizar.transaccion'])->syncRoles([$rol_coordinador, $rol_gerente, $rol_jefe_tecnico]);

        Permission::create(['name'=>'puede.crear.compras'])->assignRole($rol_compras); */
    }
}
