<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Proveedor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClienteProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //CLIENTES
        Empresa::create([
            'identificacion' => '0750784123001',
            'tipo_contribuyente' => Empresa::JURIDICA,
            'razon_social' => 'TELCONET S.A.',
            'correo' => 'servicio@telconet.ec',
            'direccion' => 'MACHALA, JUAN MONTALVO 2317'
        ]);

        Empresa::create([
            'identificacion' => '0774854123001',
            'tipo_contribuyente' => Empresa::JURIDICA,
            'razon_social' => 'NEGOCIOS Y TELEFONIA (NEDETEL) S.A.',
            'correo' => 'compras@nedetel.com',
            'direccion' => 'GUAYAQUIL, AV. PERIMETRAL KM 4 Y AV. FRANCISCO E ORELLANA'
        ]);

        Empresa::create([
            'identificacion' => '0874074123001',
            'tipo_contribuyente' => Empresa::JURIDICA,
            'razon_social' => 'ACCESSNET S.A.',
            'correo' => 'servicio@accessnetecuador.ec',
            'direccion' => 'CUENCA, RICARDO DURAN Y CAMINO VIEJO A BAÑOS'
        ]);

        Cliente::create(['empresa_id' => 1, 'parroquia_id' => 338, 'requiere_bodega' => true]);
        Cliente::create(['empresa_id' => 2, 'parroquia_id' => 525, 'requiere_bodega' => true]);
        Cliente::create(['empresa_id' => 3, 'parroquia_id' => 17, 'requiere_bodega' => true]);

        // PROVEEDORES
        Empresa::create([
            'identificacion' => '0785965234001',
            'tipo_contribuyente' => Empresa::JURIDICA,
            'razon_social' => 'FERREARMIJOS S.A.',
            'correo' => 'ventas@ferrearmijos.com.ec'
        ]);

        Empresa::create([
            'identificacion' => '0874857432001',
            'tipo_contribuyente' => Empresa::NATURAL,
            'razon_social' => 'DELGADO ROJAS LUIS LEROY',
            'correo' => 'luisleroy@gmail.com'
        ]);

        Empresa::create([
            'identificacion' => '0841526395001',
            'tipo_contribuyente' => Empresa::JURIDICA,
            'razon_social' => 'TRIONICA COMPUTACION LTDA',
            'correo' => 'ventas@trionica.com.ec'
        ]);

        Proveedor::create(['empresa_id' => 4]);
        Proveedor::create(['empresa_id' => 5]);
        Proveedor::create(['empresa_id' => 6]);
    }
}
