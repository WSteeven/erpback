<?php

namespace Database\Seeders\VentasClaro;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Src\Config\Permisos;

class PermisosModuloVentasClaroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=Database\Seeders\VentasClaro\PermisosModuloVentasClaroSeeder
     * @return void
     */
    public function run()
    {
        // Módulo principal
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_ventas_claro'])->syncRoles([User::ROL_ADMINISTRADOR]);

        // Entidades principales
        $entidadesSimples = [
            'dashboard_ventas',
            'estados_claro',
            'productos_ventas',
            'vendedores',
            'clientes_claro',
            'ventas',
            'chargebacks',
            'pagos_comisiones',
            'retenciones_chargebacks',
            'bonos_mensuales_cumplimientos',
            'bonos_trimestrales_cumplimientos',
            'planes',
            'modalidades',
            'tipos_chargebacks',
            'comisiones',
            'umbrales_ventas',
            'esquemas_comisiones',
            'escenarios_ventas_jp',
            'bonos',
            'bonos_porcentuales',
            'reportes_cobrosjp_claro',
            'reportes_pagos_claro',
            'reportes_ventas_claro'
        ];

        $acciones = [
            Permisos::ACCEDER,
            Permisos::VER,
            Permisos::CREAR,
            Permisos::EDITAR,
            Permisos::ELIMINAR
        ];

        foreach ($entidadesSimples as $entidad) {
            foreach ($acciones as $accion) {
                Permission::firstOrCreate(['name' => $accion . $entidad])->syncRoles([User::ROL_ADMINISTRADOR]);
            }
        }
    }
}
