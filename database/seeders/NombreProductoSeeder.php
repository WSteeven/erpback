<?php

namespace Database\Seeders;

use App\Models\NombreProducto;
use Illuminate\Database\Seeder;

class NombreProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        Nombres de productos
         */
        NombreProducto::create(['nombre'=>'LAPTOP']);
        NombreProducto::create(['nombre'=>'IMPRESORA']);
        NombreProducto::create(['nombre'=>'OTDR']);
        NombreProducto::create(['nombre'=>'BOBINA DE LANZAMIENTO']);
        NombreProducto::create(['nombre'=>'CARGADOR OTDR']);
        NombreProducto::create(['nombre'=>'CONECTORES SC PARA OTDR']);
        NombreProducto::create(['nombre'=>'FIBER CLEAVER']);
        NombreProducto::create(['nombre'=>'VISUAL FOULT LOCATOR']);
        NombreProducto::create(['nombre'=>'OPTICAL POWER METER']);
        NombreProducto::create(['nombre'=>'INVERSOR']);
        NombreProducto::create(['nombre'=>'CLEANER PEN SC']);
        NombreProducto::create(['nombre'=>'ROUTER 2 ANTENAS']);
        NombreProducto::create(['nombre'=>'PATCH CORD']);
        NombreProducto::create(['nombre'=>'PATCH CORD DUPLEX']);
        NombreProducto::create(['nombre'=>'CABLE DE BATERIA']);
        NombreProducto::create(['nombre'=>'CABLE DE ANTENA']);
        NombreProducto::create(['nombre'=>'ELECTRODO DE FUSIONADORA']);
        NombreProducto::create(['nombre'=>'CASETTE PARA MANGA LINEAL']);
        NombreProducto::create(['nombre'=>'CASETTE PARA MANGA DOMO']);
        NombreProducto::create(['nombre'=>'CABLE DE CONSOLA']);
        NombreProducto::create(['nombre'=>'FIBER/TRANSCEIVER']);
        NombreProducto::create(['nombre'=>'KIT DE NAVAJAS']);
    }
}
