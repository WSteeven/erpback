<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        /*Producto::create(['nombre'=>'FO SPAN 120M 12H','categoria_id'=>2]);
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
        Producto::create(['nombre'=>'KIT DE NAVAJAS','categoria_id'=>4]);*/

        $datos =  
        [
[1, 'FO SPAN 120M 12H', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[2, 'FO SPAN 120M 24H', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[3, 'FO SPAN 250M 24H', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[4, 'FO SPAN 120M 48H', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[5, 'FO SPAN 200M 48H', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[6, 'FO SPAN 300M 48H', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[7, 'FO SPAN 600M 48H', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[8, 'FO 2H (PRECONECTORIZADO 100 MTS)', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[9, 'FO 2H (PRECONECTORIZADO 150 MTS)', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[10, 'FO 2H (PRECONECTORIZADO 200 MTS)', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[11, 'FO 2H (PRECONECTORIZADO 250 MTS)', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[12, 'LAPTOP', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[13, 'IMPRESORA', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[14, 'TABLET', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[15, 'OTDR', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[16, 'CARGADOR OTDR', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[17, 'ODF 48 PUERTOS', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[18, 'MINI ODF', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[19, 'ARGOLLAS', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[20, 'BRAZOS FAROL', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[21, 'GUARDACABOS', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[22, 'CABLE TENSOR 100M', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[23, 'HERRAJE TIPO OJO', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[24, 'PREFORMADOS', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[25, 'CINTA DE ACERO 3/4', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[26, 'HEBILLAS DE ACERO 3/4', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[27, 'AMARRAS PLASTICAS', 2, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[28, 'CIZALLA', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[29, 'ZUNCHADORA', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[30, 'CINTA METRICA', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[31, 'PISTOLA PARA SILICON TUBO', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[32, 'DISCO PARA AMOLADORA 7\"', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[33, 'CORTA FRIO', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[34, 'ALICATE', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[35, 'TIJERA PARA CORTAR ACERO', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[36, 'CINCEL', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[37, 'COMBO 5LB', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[38, 'PLAYO', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[39, 'PLAYO DE PRESION', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[40, 'LINTERNA DE CABEZA ENERGIZER', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[41, 'MEDIDOR DE CORRIENTE', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[42, 'PELADOR DE CABLE', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[43, 'DESCHAQUETADORA DE BUFFER', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[44, 'SCOTER TOLSEN', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[45, 'BROCHA 4\"', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[46, 'BROCHA 5\"', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[47, 'JUEGO DE HEXAGONALES (7 PZS)', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[48, 'HEXAGONALES #10', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[49, 'TECLE DE CADENA SIN COMELON', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[50, 'MACHETE', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[51, 'SERRUCHO', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[52, 'CEGUETA', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[53, 'SIERRA', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[54, 'CURVO', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[55, 'LLAVES HEXAGONALES #24', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[56, 'DADOS PARA RACHE DE 1/2 #11/16', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[57, 'DADOS PARA RACHE DE 1/2 #17', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[58, 'DADOS PARA RACHE DE 1/2 #10', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[59, 'DADOS PARA RACHE DE 1/2 #11', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[60, 'DADOS PARA RACHE DE 1/2 #12', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[61, 'DADOS PARA RACHE DE 1/2 #13', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[62, 'DADOS PARA RACHE DE 1/2 #14', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[63, 'LLAVES MIXTAS #10', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[64, 'LLAVES MIXTAS #11', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[65, 'LLAVES MIXTAS #13', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[66, 'LLAVES MIXTAS #14', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[67, 'LLAVES MIXTAS #15', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[68, 'LLAVES MIXTAS #17', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[69, 'LLAVES MIXTAS #19', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[70, 'LLAVES MIXTAS #21', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[71, 'LLAVES MIXTAS #22', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[72, 'LLAVES MIXTAS #24', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[73, 'LLAVES MIXTAS #7/8', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[74, 'LLAVES MIXTAS #9', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[75, 'DESARMADOR ESTRELLA', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[76, 'DESARMADOR PLANO', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[77, 'DESARMADOR HEXAGONAL', 4, '2022-10-06 22:51:16', '2022-10-06 22:51:16'],
[78, 'MARTILLO PEQUEÑO', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[79, 'MARTILLO DE ACERO', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[80, 'HOJA DE SIERRA', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[81, 'GOMA UHU', 1, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[82, 'BRUJITA', 1, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[83, 'ENCHUFES', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[84, 'ALCOHOL ISOPROPILICO 1000ML', 1, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[85, 'BATERIAS ENERGIZER AA RECARGABLE', 1, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[86, 'SPRAY AZUL', 1, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[87, 'ABRAZADERA DE MANGA AMARILLA', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[88, 'ABRAZADERA DE MANGA AZUL', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[89, 'ABRAZADERA METALICA 3\"', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[90, 'ABRAZADERA METALICA 3 1/2\"', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[91, 'ABRAZADERA METALICA 1 1/2\"', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[92, 'ABRAZADERA METALICA 2 1/2\"', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[93, 'ABRAZADERA METALICA 1\"', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[94, 'ABRAZADERA METALICA 3/4\"', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[95, 'ABRAZADERA METALICA 1/2\"', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[96, 'ABRAZADERA METALICA 1/8\"', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[97, 'TACO FISHER #10', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[98, 'TACO FISHER #8', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[99, 'TACO FISHER #6', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[100, 'PERNOS DE EXPANSION 1/2', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[101, 'PERNOS DE EXPANSION 3/8', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[102, 'PERNOS DE EXPANSION 1/8', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[103, 'BROCA CINCEL PARA ROTAMARTILLO', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[104, 'BROCA PUNTA PARA ROTAMARTILLO', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[105, 'BROCA CEMENTO 4MM', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[106, 'BROCA HIERRO 3/8', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[107, 'BROCA CEMENTO 10MM', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[108, 'BROCA TOLSEN GRANDE 10MM', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[109, 'BROCA TOLSEN GRANDE 12MM', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[110, 'CINTAS AUTOFUNDENTES CAJA BLANCA', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[111, 'CINTAS AUTOFUNDENTES CAJA CAFÉ', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[112, 'CINTAS AUTOFUNDENTES CAJA CAFÉ FINA', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[113, 'CINTA AUTOFUNDENTES ROLLO GR', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[114, 'CINTA AISLANTE 3M', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[115, 'FRASCO TAPA ROJA 700CC', 1, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[116, 'FRASCO CON ATOMIZADOR 200ML', 1, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[117, 'FRASCO CON ATOMIZADOR 75ML', 1, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[118, 'FRASCO CON ATOMIZADOR 500ML', 1, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[119, 'FRASCO CON ATOMIZADOR 550ML', 1, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[120, 'CONO VIAL', 6, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[121, 'CINTA PARA ETIQUETADORA BRADY', 1, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[122, 'SOLDADORA', 5, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[123, 'SONDA AMARRILLA CON PORTASONDA', 5, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[124, 'ESMERILADORA ANGULAR', 5, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[125, 'ROTOMARTILLO', 5, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[126, 'GENERADOR', 5, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[127, 'HIDROLAVADORA', 5, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[128, 'MOTOSIERRA', 5, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[129, 'ODOMETRO', 5, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[130, 'CAMARA DE SEGURIDAD IP', 5, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[131, 'KIT DE SEGURIDAD EP', 5, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[132, 'RADIO MOTOROLLA', 5, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[133, 'BOBINA DE LANZAMIENTO', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[134, 'KIT DE CARGA PARA FUSIONADORA', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[135, 'CONECTORES SC PARA OTDR', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[136, 'SANGRADORA DE CHAQUETA', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[137, 'FIBER/TRANSCEIVER', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[138, 'PINZA DROP', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[139, 'TIJERA PARA FIBRA', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[140, 'TIJERA PARA CORTAR CHAQUETAS', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[141, 'FIBER CLEAVER', 5, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[142, 'VISUAL FOULT LOCATOR', 5, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[143, 'OPTICAL POWER METER', 5, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[144, 'INVERSOR', 5, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[145, 'CLEANER PEN SC', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[146, 'ROUTER 2 ANTENAS', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[147, 'PATCH CORD', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[148, 'PATCH CORD UTP', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[149, 'PATCH CORD DUPLEX', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[150, 'ATENUADORES', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[151, 'CABLE SERIAL', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[152, 'PIGTAIL 1.5', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[153, 'MANGA DOMO', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[154, 'MANGA LINEAL', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[155, 'CABLE UTP', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[156, 'ELETRODO ER-10 PARA SUMITOMO', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[157, 'CABLE DE BATERIA', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[158, 'CABLE DE ANTENA', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[159, 'REPUESTOS DE SANGRADORA', 6, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[160, 'ELECTRODO DE FUSIONADORA', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[161, 'CASETTE PARA MANGA LINEAL', 2, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[162, 'CASETTE PARA MANGA DOMO', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[163, 'CABLE CONSOLA', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[164, 'CABLE CONSOLA ASR 920', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[165, 'PROTECTOR DE CABLE ESPIRAL', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[166, 'CATETER', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[167, 'TUBILLO DE FUSION', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[168, 'MEDIDOR DE VOLTAJE', 5, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[169, 'PELADORA DE CABLE', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[170, 'ADAPTADOR LC/DUPLEX', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[171, 'ADAPTADOR SC/UPC', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[172, 'ADAPTADOR DUPLEX', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
[173, 'KIT DE NAVAJAS', 4, '2022-10-06 22:51:17', '2022-10-06 22:51:17'],
        ];

        foreach($datos as $fila) {
            DB::insert('INSERT INTO productos (id, nombre, categoria_id, created_at, updated_at) VALUES(?,?,?,?,?)', $fila);
        }

    }
}
