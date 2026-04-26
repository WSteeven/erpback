<?php

namespace Src\App;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

class ReporteSeguimientoService
{

private function obtenerDatosSimulados()
{
    return [
        'cliente' => 'ULTRATEL',
        'proyecto' => [
            'Cliente Principal' => 'TELEFONICA',
            'Cliente Final' => 'ULTRATEL',
            'Responsable Área' => 'ING. JUAN PABLO JIMENEZ',
            'Ciudad' => 'GUAYAQUIL',
            'Dirección' => 'Av. 9 de Octubre y Av. Eloy Alfaro',
            'Tipo de trabajo' => 'INSTALACION',
            'Tecnología' => 'GPON DATOS',
            'Fecha de culminación' => '27/04/2026',
            'Número Caso IASI' => 'IASI-2026-0001',
        ],
        'antecedentes' => 'Se necesita realizar la habilitación de un Punto CLIENTE_FINAL que tenga conexión hacia la NAP01. Implementación Telefónica.',
        'trabajos_realizados' => [
            'Se realiza la inspección en el cliente para la instalación del servicio requerido.',
            'Se realiza el tendido de cable conectorizado para la habilitación de un punto CLIENTE_FINAL desde la NAP01 hasta el cliente.',
            'Se realiza la preparación de un conector mecánico para la conectividad de la FO con la ONT.',
            'Se realiza la colocación del equipo ONT.',
            'Se realiza la verificación de potencia final en cliente.',
            'Se realiza la entrega del servicio al cliente con satisfacción.',
            'Se procede con la salida del sitio.',
        ],
        'conclusiones' => [
            'Se realiza las pruebas respectivas con el cliente para la entrega del servicio de la red GPON TELEFONICA.',
            'Se culmina la instalación de la red GPON Telefónica para el cliente ULTRATEL.',
        ],
        'equipos' => [
            ['item' => 1, 'descripcion' => 'SLIMBOX INLINE INDOOR ROSETTE 1P OVER (ROSETAS)', 'cantidad' => 1],
            ['item' => 2, 'descripcion' => 'AMARRAS PLASTICAS 30 CM (FDA)', 'cantidad' => 1],
            ['item' => 3, 'descripcion' => 'HUAWEI OPTIXSTAR HG8145X6 S/N XX', 'cantidad' => 1],
            ['item' => 4, 'descripcion' => 'CORDAO MONOFIBRA CONECTORIZADO (PATCHCORD FURUKAWA 3M)', 'cantidad' => 1],
            ['item' => 5, 'descripcion' => 'CABLE OPTICO PRECONECT 150M', 'cantidad' => 1],
            ['item' => 6, 'descripcion' => 'CABLE OPTICO DROP 2H 100M', 'cantidad' => 7],
            ['item' => 7, 'descripcion' => 'KIT WITH 10 UNIVERSAL OPTICAL FIELD', 'cantidad' => 1],
            ['item' => 8, 'descripcion' => 'GANCHOS TELEFONICOS', 'cantidad' => 7],
            ['item' => 9, 'descripcion' => 'TUBILLOS', 'cantidad' => 1],
            ['item' => 10, 'descripcion' => 'ROSETA 4P', 'cantidad' => 1],
            ['item' => 11, 'descripcion' => 'CINTA AISLANTE', 'cantidad' => 1],
            ['item' => 12, 'descripcion' => 'MINIMANGA', 'cantidad' => 1],
        ],
        'ubicacion' => [
            'Cliente' => 'CLIENTE_FINAL',
            'Latitud' => '-2.204425',
            'Longitud' => '-79.885405',
            'Dirección' => 'Av. 9 de Octubre y Av. Eloy Alfaro, Guayaquil',
            'NAP' => 'NAP01',
            'NAP Latitud' => '-2.205000',
            'NAP Longitud' => '-79.886000',
            'Manga' => 'MANGA-01',
        ],
        'fotografias' => [
            ['titulo' => 'Panorámica Cliente', 'ruta' => '/storage/seguimiento/D8YNx2I47f.png'],
            ['titulo' => 'Conexión NAP', 'ruta' => '/storage/seguimiento/NQqyRd80h2.jpeg'],
        ],
        'pruebas' => [
            ['nombre' => 'Pruebas de Potencia', 'resultado' => 'Potencia de referencia: -23 dBm; resultado PASS'],
            ['nombre' => 'Pruebas de Velocidad', 'resultado' => 'Velocidad de subida 50 Mbps / bajada 150 Mbps; resultado PASS'],
        ],
        'oracle' => 'Se registra en Oracle el caso IASI y se documenta el estado del servicio como ACTIVADO.',
        'acta_aceptacion' => 'El cliente acepta la instalación y entrega del servicio, firmando el acta de aceptación con conformidad.',
    ];
}
/**
 * El informe debe contener esta estructura:
 * 1. Proyecto e información del cliente (Cliente principal
 * Cliente Final
 * Responsable Área
 * Ciudad
 * Dirección
 * Tipo de trabajo
 * Tecnología
 * Fecha de culminación
 * Número Caso IASI,
 * Imagen registro de entrada (opc, si la hubiere)
 * Imagen registro de salida (opc, si la hubiere)
 * 
 * 2. Antecedentes: 
 * Una linea narrada con lo que se va hacer (descripción de la subtarea)
 * e información que solo el cliente la base (muy dificil, porque el sistema no trae eso)
 * 
 * 3. Trabajos realizados:
 * listado detallado de actividades, 
 * Resumen de lo más heavy (FO, preconectorizada, mangas , etc.)
 * 
 * 4. Conclusiones: algo como "Se realiza las pruebas respectivas con el cliente para la entrega del servicio de la red GPON TELEFONICA." y  
 * "Se culmina la instalación de la red GPON Telefónica para el cliente CLIENTE_FINAL" 
 * 5. Detalle de los equipos: todo el material utilizado, con su cantidad y descripción (ONT, Patchcord, etc.)
 * 6. Ubicación del cliente: 
 * Foto si la hubiere
 * 7. Coordenadas del cliente, la nap, manga, rbs, etc.
 * 7. Informe fotográfico (todas las fotos ordenadas )
 * 8. Pruebas de protocolo
 * 9. Oracle y
 * 10. Acta de aceptación del cliente
 *
 */
public function exportarSeguimientoWord($subtarea_id)
{
    // Simulación de datos dinámicos
    $data = $this->obtenerDatosSimulados();

    $phpWord = new PhpWord();

    $phpWord->setDefaultFontName('Arial');
    $phpWord->setDefaultFontSize(10);

    $section = $phpWord->addSection();
    $section->addText('INFORME TÉCNICO', ['bold' => true, 'size' => 16], ['alignment' => Jc::CENTER]);
    $section->addTextBreak(1);

    $tableStyle = [
        'borderSize' => 6,
        'borderColor' => '000000',
        'cellMargin' => 80,
    ];
    $phpWord->addTableStyle('tablaGeneral', $tableStyle);

    $section->addText('1. Proyecto e información del cliente', ['bold' => true, 'size' => 12]);
    $table = $section->addTable('tablaGeneral');
    foreach ($data['proyecto'] as $label => $valor) {
        $table->addRow();
        $table->addCell(4000)->addText($label, ['bold' => true]);
        $table->addCell(6000)->addText($valor);
    }

    $section->addTextBreak(1);
    $section->addText('2. Antecedentes', ['bold' => true, 'size' => 12]);
    $section->addText($data['antecedentes']);
    $section->addTextBreak(1);

    $section->addText('3. Trabajos realizados', ['bold' => true, 'size' => 12]);
    foreach ($data['trabajos_realizados'] as $trabajo) {
        $section->addText("• {$trabajo}", ['size' => 10]);
    }

    $section->addTextBreak(1);
    $section->addText('4. Conclusiones', ['bold' => true, 'size' => 12]);
    foreach ($data['conclusiones'] as $conclusion) {
        $section->addText($conclusion);
    }

    $section->addTextBreak(1);
    $section->addText('5. Detalle de los equipos', ['bold' => true, 'size' => 12]);
    $tableEquipos = $section->addTable('tablaGeneral');
    $tableEquipos->addRow();
    $tableEquipos->addCell(1500)->addText('Item', ['bold' => true]);
    $tableEquipos->addCell(6500)->addText('Descripción', ['bold' => true]);
    $tableEquipos->addCell(1500)->addText('Cantidad', ['bold' => true]);
    foreach ($data['equipos'] as $equipo) {
        $tableEquipos->addRow();
        $tableEquipos->addCell(1500)->addText($equipo['item']);
        $tableEquipos->addCell(6500)->addText($equipo['descripcion']);
        $tableEquipos->addCell(1500)->addText($equipo['cantidad']);
    }

    $section->addPageBreak();
    $section->addText('6. Ubicación del cliente', ['bold' => true, 'size' => 12]);
    $ubicacionTable = $section->addTable('tablaGeneral');
    foreach ($data['ubicacion'] as $label => $valor) {
        $ubicacionTable->addRow();
        $ubicacionTable->addCell(4000)->addText($label, ['bold' => true]);
        $ubicacionTable->addCell(6000)->addText($valor);
    }

    $section->addTextBreak(1);
    $section->addText('7. Informe fotográfico', ['bold' => true, 'size' => 12]);
    foreach ($data['fotografias'] as $foto) {
        $section->addText($foto['titulo'], ['bold' => true]);
        $imagePath = $this->obtenerRutaImagenWord($foto['ruta']);
        if (!file_exists($imagePath)) {
            throw new \RuntimeException("Imagen no encontrada: {$imagePath}");
        }
        $section->addImage($imagePath, ['width' => 400, 'alignment' => Jc::CENTER]);
        $section->addTextBreak(1);
    }

    $section->addText('8. Pruebas de protocolo', ['bold' => true, 'size' => 12]);
    $pruebaTable = $section->addTable('tablaGeneral');
    $pruebaTable->addRow();
    $pruebaTable->addCell(4000)->addText('Prueba', ['bold' => true]);
    $pruebaTable->addCell(6000)->addText('Resultado', ['bold' => true]);
    foreach ($data['pruebas'] as $prueba) {
        $pruebaTable->addRow();
        $pruebaTable->addCell(4000)->addText($prueba['nombre']);
        $pruebaTable->addCell(6000)->addText($prueba['resultado']);
    }

    $section->addTextBreak(1);
    $section->addText('9. Oracle', ['bold' => true, 'size' => 12]);
    $section->addText($data['oracle']);

    $section->addTextBreak(1);
    $section->addText('10. Acta de aceptación del cliente', ['bold' => true, 'size' => 12]);
    $section->addText($data['acta_aceptacion']);

    $fileName = "Informe_Tecnico_{$data['cliente']}.docx";
    $tempFile = storage_path($fileName);
    $writer = IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save($tempFile);

    return response()->download($tempFile)->deleteFileAfterSend(true);
}

private function obtenerRutaImagenWord(string $ruta): string
{
    $ruta = preg_replace('#^/storage#', '', $ruta);
    $ruta = ltrim($ruta, '/');

    return storage_path('app/public/' . $ruta);
}


}
