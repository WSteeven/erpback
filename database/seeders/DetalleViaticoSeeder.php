<?php

namespace Database\Seeders;

use App\Models\FondosRotativos\Gasto\DetalleViatico;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetalleViaticoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DetalleViatico::insert([
            [
                'descripcion' => 'ALIMENTACIÓN',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'ARRIENDO EVENTUALES POR PROYECTOS',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'HERRAMIENTAS Y EQUIPOS',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'BOLETO DE TRANSPORTE',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'BUS URBANO',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'COMBUSTIBLE',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'COMPRA',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'COPIAS DE LLAVE',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'DAÑO A TERCEROS',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'ENVÍO DE ENCOMIENDA',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'GUARDIANÍA',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'HOSPEDAJE',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'IMPRESIONES',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'LAVANDERÍA',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'PARQUEADERO',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'PEAJE',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'PRUEBA DE COVID 19',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'REPARACIÓN Y MANTENIMIENTO HERRAMIENTA Y EQUIPOS',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'REPUESTOS TECNOLÓGICOS',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'RETIRO DE VALIJAS AL COBRO',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'TAXI',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'TRÁMITES ADMINISTRATIVOS',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'TRANSPORTE DE BOBINAS',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'MANTENIMIENTO Y REPARACION DE VEHÍCULO',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'TRAMITES ADMINISTRATIVOS DE VEHICULO',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
            [
                'descripcion' => 'OBRAS CIVILES',
                'autorizacion' => 'SI',
                'id_estatus' => 1,
            ],
        ]);
    }
}
