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
        Categoria::create(['nombre'=>'SUMINISTROS']);   1
        Categoria::create(['nombre'=>'MATERIALES']);    2
        Categoria::create(['nombre'=>'INFORMATICA']);   3
        Categoria::create(['nombre'=>'HERRAMIENTAS']);  4
        Categoria::create(['nombre'=>'EQUIPOS']);       5
        Categoria::create(['nombre'=>'EPP']);           6
         */
        //FIBRAS
        Producto::create(['nombre'=>'FO SPAN 120M 12H','categoria_id'=>2]);
        Producto::create(['nombre'=>'FO SPAN 120M 24H','categoria_id'=>2]);
        Producto::create(['nombre'=>'FO SPAN 250M 24H','categoria_id'=>2]);
        Producto::create(['nombre'=>'FO SPAN 120M 48H','categoria_id'=>2]);
        Producto::create(['nombre'=>'FO SPAN 200M 48H','categoria_id'=>2]);
        Producto::create(['nombre'=>'FO SPAN 300M 48H','categoria_id'=>2]);
        Producto::create(['nombre'=>'FO SPAN 600M 48H','categoria_id'=>2]);
        Producto::create(['nombre'=>'FO 2H (PRECONECTORIZADO 100 MTS)','categoria_id'=>2]);
        Producto::create(['nombre'=>'FO 2H (PRECONECTORIZADO 150 MTS)','categoria_id'=>2]);
        Producto::create(['nombre'=>'FO 2H (PRECONECTORIZADO 200 MTS)','categoria_id'=>2]);
        Producto::create(['nombre'=>'FO 2H (PRECONECTORIZADO 250 MTS)','categoria_id'=>2]);

        Producto::create(['nombre'=>'LAPTOP', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'IMPRESORA', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'TABLET', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'OTDR', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'CARGADOR OTDR','categoria_id'=>4]);
        Producto::create(['nombre'=>'ODF 48 PUERTOS', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'MINI ODF', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'ARGOLLAS', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'BRAZOS FAROL', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'GUARDACABOS', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'CABLE TENSOR 100M', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'HERRAJE TIPO OJO', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'PREFORMADOS', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'CINTA DE ACERO 3/4', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'HEBILLAS DE ACERO 3/4', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'AMARRAS PLASTICAS', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'CIZALLA', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'ZUNCHADORA', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'CINTA METRICA', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'PISTOLA PARA SILICON TUBO', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'DISCO PARA AMOLADORA 7"', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'CORTA FRIO', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'ALICATE', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'TIJERA PARA CORTAR ACERO', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'CINCEL', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'COMBO 5LB', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'PLAYO', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'PLAYO DE PRESION', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'LINTERNA DE CABEZA ENERGIZER', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'MEDIDOR DE CORRIENTE', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'PELADOR DE CABLE', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'DESCHAQUETADORA DE BUFFER', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'SCOTER TOLSEN', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'BROCHA 4"', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'BROCHA 5"', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'JUEGO DE HEXAGONALES (7 PZS)', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'HEXAGONALES #10', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'TECLE DE CADENA SIN COMELON', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'MACHETE', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'SERRUCHO', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'CEGUETA', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'SIERRA', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'CURVO', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'LLAVES HEXAGONALES #24', 'categoria_id'=>4]);

        Producto::create(['nombre'=>'DADOS PARA RACHE DE 1/2 #11/16', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'DADOS PARA RACHE DE 1/2 #17', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'DADOS PARA RACHE DE 1/2 #10', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'DADOS PARA RACHE DE 1/2 #11', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'DADOS PARA RACHE DE 1/2 #12', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'DADOS PARA RACHE DE 1/2 #13', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'DADOS PARA RACHE DE 1/2 #14', 'categoria_id'=>4]);

        Producto::create(['nombre'=>'LLAVES MIXTAS #10', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'LLAVES MIXTAS #11', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'LLAVES MIXTAS #13', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'LLAVES MIXTAS #14', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'LLAVES MIXTAS #15', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'LLAVES MIXTAS #17', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'LLAVES MIXTAS #19', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'LLAVES MIXTAS #21', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'LLAVES MIXTAS #22', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'LLAVES MIXTAS #24', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'LLAVES MIXTAS #7/8', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'LLAVES MIXTAS #9', 'categoria_id'=>4]);
        
        Producto::create(['nombre'=>'DESARMADOR ESTRELLA', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'DESARMADOR PLANO', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'DESARMADOR HEXAGONAL', 'categoria_id'=>4]);
        
        Producto::create(['nombre'=>'MARTILLO PEQUEÑO', 'categoria_id'=>4]);
        Producto::create(['nombre'=>'MARTILLO DE ACERO', 'categoria_id'=>4]);

        Producto::create(['nombre'=>'HOJA DE SIERRA', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'GOMA UHU', 'categoria_id'=>1]);
        Producto::create(['nombre'=>'BRUJITA', 'categoria_id'=>1]);
        Producto::create(['nombre'=>'ENCHUFES', 'categoria_id'=>2]);

        Producto::create(['nombre'=>'ALCOHOL ISOPROPILICO 1000ML', 'categoria_id'=>1]);
        Producto::create(['nombre'=>'BATERIAS ENERGIZER AA RECARGABLE', 'categoria_id'=>1]);
        Producto::create(['nombre'=>'SPRAY AZUL', 'categoria_id'=>1]);

        Producto::create(['nombre'=>'ABRAZADERA DE MANGA AMARILLA', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'ABRAZADERA DE MANGA AZUL', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'ABRAZADERA METALICA 3"', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'ABRAZADERA METALICA 3 1/2"', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'ABRAZADERA METALICA 1 1/2"', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'ABRAZADERA METALICA 2 1/2"', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'ABRAZADERA METALICA 1"', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'ABRAZADERA METALICA 3/4"', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'ABRAZADERA METALICA 1/2"', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'ABRAZADERA METALICA 1/8"', 'categoria_id'=>2]);

        Producto::create(['nombre'=>'TACO FISHER #10', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'TACO FISHER #8', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'TACO FISHER #6', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'PERNOS DE EXPANSION 1/2', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'PERNOS DE EXPANSION 3/8', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'PERNOS DE EXPANSION 1/8', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'BROCA CINCEL PARA ROTAMARTILLO', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'BROCA PUNTA PARA ROTAMARTILLO', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'BROCA CEMENTO 4MM', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'BROCA HIERRO 3/8', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'BROCA CEMENTO 10MM', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'BROCA TOLSEN GRANDE 10MM', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'BROCA TOLSEN GRANDE 12MM', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'CINTAS AUTOFUNDENTES CAJA BLANCA', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'CINTAS AUTOFUNDENTES CAJA CAFÉ', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'CINTAS AUTOFUNDENTES CAJA CAFÉ FINA', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'CINTA AUTOFUNDENTES ROLLO GR', 'categoria_id'=>2]);
        Producto::create(['nombre'=>'CINTA AISLANTE 3M', 'categoria_id'=>2]);

        Producto::create(['nombre'=>'FRASCO TAPA ROJA 700CC', 'categoria_id'=>1]);
        Producto::create(['nombre'=>'FRASCO CON ATOMIZADOR 200ML', 'categoria_id'=>1]);
        Producto::create(['nombre'=>'FRASCO CON ATOMIZADOR 75ML', 'categoria_id'=>1]);
        Producto::create(['nombre'=>'FRASCO CON ATOMIZADOR 500ML', 'categoria_id'=>1]);
        Producto::create(['nombre'=>'FRASCO CON ATOMIZADOR 550ML', 'categoria_id'=>1]);

        Producto::create(['nombre'=>'CONO VIAL', 'categoria_id'=>6]);
        Producto::create(['nombre'=>'CINTA PARA ETIQUETADORA BRADY', 'categoria_id'=>1]);
        
        Producto::create(['nombre'=>'SOLDADORA', 'categoria_id'=>5]);
        Producto::create(['nombre'=>'SONDA AMARRILLA CON PORTASONDA', 'categoria_id'=>5]);
        Producto::create(['nombre'=>'ESMERILADORA ANGULAR', 'categoria_id'=>5]);
        Producto::create(['nombre'=>'ROTOMARTILLO', 'categoria_id'=>5]);
        Producto::create(['nombre'=>'GENERADOR', 'categoria_id'=>5]);
        Producto::create(['nombre'=>'HIDROLAVADORA', 'categoria_id'=>5]);
        Producto::create(['nombre'=>'MOTOSIERRA', 'categoria_id'=>5]);
        Producto::create(['nombre'=>'ODOMETRO', 'categoria_id'=>5]);
        Producto::create(['nombre'=>'CAMARA DE SEGURIDAD IP', 'categoria_id'=>5]);
        Producto::create(['nombre'=>'KIT DE SEGURIDAD EP', 'categoria_id'=>5]);
        Producto::create(['nombre'=>'RADIO MOTOROLLA', 'categoria_id'=>5]);
        

        Producto::create(['nombre'=>'BOBINA DE LANZAMIENTO','categoria_id'=>4]);
        Producto::create(['nombre'=>'KIT DE CARGA PARA FUSIONADORA','categoria_id'=>4]);
        Producto::create(['nombre'=>'CONECTORES SC PARA OTDR','categoria_id'=>4]);
        Producto::create(['nombre'=>'SANGRADORA DE CHAQUETA','categoria_id'=>4]);
        Producto::create(['nombre'=>'FIBER/TRANSCEIVER','categoria_id'=>4]);
        Producto::create(['nombre'=>'PINZA DROP','categoria_id'=>4]);
        Producto::create(['nombre'=>'TIJERA PARA FIBRA','categoria_id'=>4]);
        Producto::create(['nombre'=>'TIJERA PARA CORTAR CHAQUETAS','categoria_id'=>4]);
        Producto::create(['nombre'=>'FIBER CLEAVER','categoria_id'=>5]);
        Producto::create(['nombre'=>'VISUAL FOULT LOCATOR','categoria_id'=>5]);
        Producto::create(['nombre'=>'OPTICAL POWER METER','categoria_id'=>5]);
        Producto::create(['nombre'=>'INVERSOR','categoria_id'=>5]);
        Producto::create(['nombre'=>'CLEANER PEN SC','categoria_id'=>4]);
        Producto::create(['nombre'=>'ROUTER 2 ANTENAS','categoria_id'=>4]);
        Producto::create(['nombre'=>'PATCH CORD','categoria_id'=>4]);
        Producto::create(['nombre'=>'PATCH CORD UTP','categoria_id'=>4]);
        Producto::create(['nombre'=>'PATCH CORD DUPLEX','categoria_id'=>4]);
        Producto::create(['nombre'=>'ATENUADORES','categoria_id'=>4]);
        Producto::create(['nombre'=>'CABLE SERIAL','categoria_id'=>2]);
        Producto::create(['nombre'=>'PIGTAIL 1.5','categoria_id'=>4]);
        Producto::create(['nombre'=>'MANGA DOMO','categoria_id'=>2]);
        Producto::create(['nombre'=>'MANGA LINEAL','categoria_id'=>2]);
        Producto::create(['nombre'=>'CABLE UTP','categoria_id'=>2]);
        Producto::create(['nombre'=>'ELETRODO ER-10 PARA SUMITOMO','categoria_id'=>2]);
        Producto::create(['nombre'=>'CABLE DE BATERIA','categoria_id'=>2]);
        Producto::create(['nombre'=>'CABLE DE ANTENA','categoria_id'=>2]);
        Producto::create(['nombre'=>'REPUESTOS DE SANGRADORA','categoria_id'=>6]);
        Producto::create(['nombre'=>'ELECTRODO DE FUSIONADORA','categoria_id'=>2]);
        Producto::create(['nombre'=>'CASETTE PARA MANGA LINEAL','categoria_id'=>2]);
        Producto::create(['nombre'=>'CASETTE PARA MANGA DOMO','categoria_id'=>4]);
        Producto::create(['nombre'=>'CABLE CONSOLA','categoria_id'=>4]);
        Producto::create(['nombre'=>'CABLE CONSOLA ASR 920','categoria_id'=>4]);
        Producto::create(['nombre'=>'PROTECTOR DE CABLE ESPIRAL','categoria_id'=>4]);
        Producto::create(['nombre'=>'CATETER','categoria_id'=>4]);
        Producto::create(['nombre'=>'TUBILLO DE FUSION','categoria_id'=>4]);
        Producto::create(['nombre'=>'MEDIDOR DE VOLTAJE','categoria_id'=>5]);
        Producto::create(['nombre'=>'PELADORA DE CABLE','categoria_id'=>4]);
        Producto::create(['nombre'=>'ADAPTADOR LC/DUPLEX','categoria_id'=>4]);
        Producto::create(['nombre'=>'ADAPTADOR SC/UPC','categoria_id'=>4]);
        Producto::create(['nombre'=>'ADAPTADOR DUPLEX','categoria_id'=>4]);
        Producto::create(['nombre'=>'KIT DE NAVAJAS','categoria_id'=>4]);

    }
}
