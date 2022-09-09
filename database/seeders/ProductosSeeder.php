<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductosSeeder extends Seeder
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
        /* 
        Categoria::create(['nombre'=>'UTILITARIOS']);
        Categoria::create(['nombre'=>'UNIFORMES']);
        Categoria::create(['nombre'=>'EPP']);
        Categoria::create(['nombre'=>'INFORMATICA']);
        Categoria::create(['nombre'=>'HERRAMIENTAS']);
        Categoria::create(['nombre'=>'MATERIALES']);
        Categoria::create(['nombre'=>'EQUIPOS']);
         */
        Producto::create(['nombre'=>'LAPTOP', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'IMPRESORA', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'TABLET', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'OTDR', 'categoria_id'=>7]);
        Producto::create(['nombre'=>'BOBINA DE LANZAMIENTO','categoria_id'=>7]);
        Producto::create(['nombre'=>'CARGADOR OTDR','categoria_id'=>7]);
        Producto::create(['nombre'=>'KIT DE CARGA PARA FUSIONADORA','categoria_id'=>7]);
        Producto::create(['nombre'=>'CONECTORES SC PARA OTDR','categoria_id'=>5]);
        Producto::create(['nombre'=>'SANGRADORA DE CHAQUETA','categoria_id'=>5]);
        Producto::create(['nombre'=>'FIBER/TRANSCEIVER','categoria_id'=>5]);
        Producto::create(['nombre'=>'PINZA DROP','categoria_id'=>5]);
        Producto::create(['nombre'=>'TIJERA PARA FIBRA','categoria_id'=>5]);
        Producto::create(['nombre'=>'FIBER CLEAVER','categoria_id'=>5]);
        Producto::create(['nombre'=>'VISUAL FOULT LOCATOR','categoria_id'=>5]);
        Producto::create(['nombre'=>'OPTICAL POWER METER','categoria_id'=>5]);
        Producto::create(['nombre'=>'INVERSOR','categoria_id'=>5]);
        Producto::create(['nombre'=>'CLEANER PEN SC','categoria_id'=>5]);
        Producto::create(['nombre'=>'ROUTER 2 ANTENAS','categoria_id'=>4]);
        Producto::create(['nombre'=>'PATCH CORD','categoria_id'=>5]);
        Producto::create(['nombre'=>'PATCH CORD DUPLEX','categoria_id'=>5]);
        Producto::create(['nombre'=>'ATENUADORES','categoria_id'=>5]);
        Producto::create(['nombre'=>'CABLE SERIAL','categoria_id'=>5]);
        Producto::create(['nombre'=>'PIGTAIL','categoria_id'=>5]);
        Producto::create(['nombre'=>'MANGA DOMO','categoria_id'=>6]);
        Producto::create(['nombre'=>'MANGA LINEAL','categoria_id'=>6]);
        Producto::create(['nombre'=>'CABLE UTP','categoria_id'=>6]);
        Producto::create(['nombre'=>'ELETRODO ER-10 PARA SUMITOMO','categoria_id'=>6]);
        Producto::create(['nombre'=>'CABLE DE BATERIA','categoria_id'=>6]);
        Producto::create(['nombre'=>'CABLE DE ANTENA','categoria_id'=>6]);
        Producto::create(['nombre'=>'REPUESTOS DE SANGRADORA','categoria_id'=>6]);
        Producto::create(['nombre'=>'ELECTRODO DE FUSIONADORA','categoria_id'=>6]);
        Producto::create(['nombre'=>'CASETTE PARA MANGA LINEAL','categoria_id'=>6]);
        Producto::create(['nombre'=>'CASETTE PARA MANGA DOMO','categoria_id'=>6]);
        Producto::create(['nombre'=>'CABLE DE CONSOLA','categoria_id'=>6]);
        Producto::create(['nombre'=>'PROTECTOR DE CABLE ESPIRAL','categoria_id'=>6]);
        Producto::create(['nombre'=>'CATETER','categoria_id'=>6]);
        Producto::create(['nombre'=>'TUBILLO DE FUSION','categoria_id'=>6]);
        Producto::create(['nombre'=>'MEDIDOR DE VOLTAJE','categoria_id'=>6]);
        Producto::create(['nombre'=>'PELADORA DE CABLE','categoria_id'=>6]);
        Producto::create(['nombre'=>'ADAPTADOR SC','categoria_id'=>6]);
        Producto::create(['nombre'=>'KIT DE NAVAJAS','categoria_id'=>6]);

        //FIBRAS
        Producto::create(['nombre'=>'ADSS CABLE SPAN 120M 48 FO G652D ','categoria_id'=>6]);
        Producto::create(['nombre'=>'ADSS CABLE SPAN 250M 24 FO B1.3 ','categoria_id'=>6]);
        Producto::create(['nombre'=>'FIBRA DROP BOW TIE SHAPE 2 FO G657A2 METRAJE (PRECONECTORIZADO 100 MTS','categoria_id'=>6]);
        Producto::create(['nombre'=>'FIBRA DROP BOW TIE SHAPE 2 FO G657A2 METRAJE (PRECONECTORIZADO 150 MTS','categoria_id'=>6]);
        Producto::create(['nombre'=>'FIBRA DROP BOW TIE SHAPE 2 FO G657A2 METRAJE (PRECONECTORIZADO 200 MTS','categoria_id'=>6]);
        Producto::create(['nombre'=>'FIBRA DROP BOW TIE SHAPE 2 FO G657A2 METRAJE (PRECONECTORIZADO 250 MTS','categoria_id'=>6]);
    }
}
