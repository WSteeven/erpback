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
        NombreProducto::create(['nombre'=>'TABLET']);
        NombreProducto::create(['nombre'=>'OTDR']);
        NombreProducto::create(['nombre'=>'BOBINA DE LANZAMIENTO']);
        NombreProducto::create(['nombre'=>'CARGADOR OTDR']);
        NombreProducto::create(['nombre'=>'KIT DE CARGA PARA FUSIONADORA']);
        NombreProducto::create(['nombre'=>'CONECTORES SC PARA OTDR']);
        NombreProducto::create(['nombre'=>'SANGRADORA DE CHAQUETA']);
        NombreProducto::create(['nombre'=>'FIBER/TRANSCEIVER']);
        NombreProducto::create(['nombre'=>'PINZA DROP']);
        NombreProducto::create(['nombre'=>'TIJERA PARA FIBRA']);
        NombreProducto::create(['nombre'=>'FIBER CLEAVER']);
        NombreProducto::create(['nombre'=>'VISUAL FOULT LOCATOR']);
        NombreProducto::create(['nombre'=>'OPTICAL POWER METER']);
        NombreProducto::create(['nombre'=>'INVERSOR']);
        NombreProducto::create(['nombre'=>'CLEANER PEN SC']);
        NombreProducto::create(['nombre'=>'ROUTER 2 ANTENAS']);
        NombreProducto::create(['nombre'=>'PATCH CORD']);
        NombreProducto::create(['nombre'=>'PATCH CORD DUPLEX']);
        NombreProducto::create(['nombre'=>'ATENUADORES']);
        NombreProducto::create(['nombre'=>'CABLE SERIAL']);
        NombreProducto::create(['nombre'=>'PIGTAIL']);
        NombreProducto::create(['nombre'=>'MANGA DOMO']);
        NombreProducto::create(['nombre'=>'MANGA LINEAL']);
        NombreProducto::create(['nombre'=>'CABLE UTP']);
        NombreProducto::create(['nombre'=>'ELETRODO ER-10 PARA SUMITOMO']);
        NombreProducto::create(['nombre'=>'CABLE DE BATERIA']);
        NombreProducto::create(['nombre'=>'CABLE DE ANTENA']);
        NombreProducto::create(['nombre'=>'REPUESTOS DE SANGRADORA']);
        NombreProducto::create(['nombre'=>'ELECTRODO DE FUSIONADORA']);
        NombreProducto::create(['nombre'=>'CASETTE PARA MANGA LINEAL']);
        NombreProducto::create(['nombre'=>'CASETTE PARA MANGA DOMO']);
        NombreProducto::create(['nombre'=>'CABLE DE CONSOLA']);
        NombreProducto::create(['nombre'=>'PROTECTOR DE CABLE ESPIRAL']);
        NombreProducto::create(['nombre'=>'CATETER']);
        NombreProducto::create(['nombre'=>'TUBILLO DE FUSION']);
        NombreProducto::create(['nombre'=>'MEDIDOR DE VOLTAJE']);
        NombreProducto::create(['nombre'=>'PELADORA DE CABLE']);
        NombreProducto::create(['nombre'=>'ADAPTADOR SC']);
        NombreProducto::create(['nombre'=>'KIT DE NAVAJAS']);

        //FIBRAS
        NombreProducto::create(['nombre'=>'ADSS CABLE SPAN 120M 48 FO G652D ']);
        NombreProducto::create(['nombre'=>'ADSS CABLE SPAN 250M 24 FO B1.3 ']);
        NombreProducto::create(['nombre'=>'FIBRA DROP BOW TIE SHAPE 2 FO G657A2 METRAJE (PRECONECTORIZADO 100 MTS']);
        NombreProducto::create(['nombre'=>'FIBRA DROP BOW TIE SHAPE 2 FO G657A2 METRAJE (PRECONECTORIZADO 150 MTS']);
        NombreProducto::create(['nombre'=>'FIBRA DROP BOW TIE SHAPE 2 FO G657A2 METRAJE (PRECONECTORIZADO 200 MTS']);
        NombreProducto::create(['nombre'=>'FIBRA DROP BOW TIE SHAPE 2 FO G657A2 METRAJE (PRECONECTORIZADO 250 MTS']);
    }
}
