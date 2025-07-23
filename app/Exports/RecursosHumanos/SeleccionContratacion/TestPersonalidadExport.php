<?php

namespace App\Exports\RecursosHumanos\SeleccionContratacion;


use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class TestPersonalidadExport implements FromSpreadsheet
{
    protected array $respuestas;
    protected string $plantillaPath;

    public function __construct(array $respuestas)
    {
        $this->respuestas = $respuestas;
        $this->plantillaPath = storage_path('app/plantillas/plantilla_test_16pf.xlsx');
    }

    /**
     * @throws Exception
     */
    public function spreadsheet(): Spreadsheet
    {
//        $spreadsheet = IOFactory::load($this->plantillaPath);
//        $hoja = $spreadsheet->getSheetByName('Ingreso Datos');
//
//        $rango_inicio_fila = 3;
//        $rango_fin_fila = 18;
//        $rango_inicio_col = Coordinate::columnIndexFromString('A');
//        $rango_fin_col = Coordinate::columnIndexFromString('X');
//
//        foreach ($this->respuestas as $pregunta => $letra) {
//            $valor_numerico = match ($letra) {
//                'A' => 1,
//                'B' => 2,
//                'C' => 3,
//            };
//
//            $coordenada = null;
//            for ($fila = $rango_inicio_fila; $fila <= $rango_fin_fila; $fila++) {
//                for ($col = $rango_inicio_col; $col <= $rango_fin_col; $col++) {
//                    $valor = $hoja->getCell([$col, $fila])->getValue();
//                    if ((string)$valor === (string)$pregunta) {
//                        $coordenada = ['col' => $col + 1, 'fila' => $fila];
//                        break 2;
//                    }
//                }
//            }
//
//            if ($coordenada) {
//                $hoja->setCellValue([$coordenada['col'],$coordenada['fila']],$valor_numerico);
//            }
//        }
//
//        return $spreadsheet;
        return new Spreadsheet();
    }
}
