<?php

namespace Database\Seeders\Sistema;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Config\Permisos;

class BodegaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\Sistema\BodegaSeeder"
     * @return void
     */
    public function run()
    {
        $bodega = Role::firstOrCreate(['name' => User::ROL_BODEGA]);

        Permission::firstOrCreate(['name' => Permisos::VER . 'modulo_administracion'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'modulo_bodega'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'autorizaciones'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'categorias'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'codigos_clientes'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'condiciones'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'control_stock'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'control_stock'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'control_stock'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'control_stock'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'detalles'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'detalles'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'estados_transacciones'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'hilos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'hilos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'hilos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'hilos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'inventarios'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'inventarios'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'inventarios'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'marcas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'marcas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'marcas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'marcas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'modelos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'modelos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'modelos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'modelos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'movimientos_productos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'movimientos_productos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'movimientos_productos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'movimientos_productos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'motivos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'motivos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'motivos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'prestamos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'prestamos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'prestamos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'productos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'sucursales'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'traspasos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'traspasos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'traspasos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'transferencias'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'transferencias'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'transferencias'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'tipos_fibras'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'tipos_fibras'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'tipos_fibras'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'tipos_fibras'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'tipos_transacciones'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'transacciones_egresos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'transacciones_egresos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'transacciones_ingresos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'transacciones_ingresos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'transacciones'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'transacciones'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'transacciones'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'unidades_medidas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'unidades_medidas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'unidades_medidas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'unidades_medidas'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'materiales'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'liquidacion'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'detalle_fondo'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'sub_detalle_fondo'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'saldo'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'saldo'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'saldo'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'reporte_saldo_actual'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'reporte_saldo_actual'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ELIMINAR . 'reporte_saldo_actual'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'materiales_empleados'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'detalles'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'sucursales'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'transferencias'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'preingresos_materiales'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ANULAR . 'egresos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'productos_bienes'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::VER . 'dashboard_bodega'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::GESTIONAR . 'devoluciones'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'dashboard_bodega'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'materiales_empleados'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'traspasos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_administracion'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'codigos_clientes'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'hilos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'tipos_fibras'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'motivos'])->assignRole($bodega);
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'marcas'])->assignRole($bodega);
    }
}
