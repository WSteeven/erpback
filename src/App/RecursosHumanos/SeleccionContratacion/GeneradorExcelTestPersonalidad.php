<?php

namespace Src\App\RecursosHumanos\SeleccionContratacion;

use App\Models\Empleado;
use App\Models\RecursosHumanos\SeleccionContratacion\EvaluacionPersonalidad;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulante;
use App\Models\Sistema\PlantillaBase;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Src\Config\Constantes;

class GeneradorExcelTestPersonalidad
{
    /**
     * @param Postulacion $postulacion
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws Exception
     */
    public function generar(Postulacion $postulacion)
    {
        // 1. Obtener la evaluación y respuestas
        $evaluacion = $postulacion->evaluacionPersonalidad;
        $respuestas = $this->mapearRespuestas($evaluacion);

        // 2. Cargar plantilla
        $spreadsheet = $this->cargarPlantilla();

        $hojaDatos = $spreadsheet->getSheetByName('Ingreso Datos');
        $hojaResultados = $spreadsheet->getSheetByName('Resultados');

        // 3. Insertar respuestas y datos personales
        $this->insertarRespuestasEnExcel($hojaDatos, $respuestas);
        $this->insertarDatosPersonales($hojaDatos, $postulacion, $evaluacion);

        // 4. Extraer resultados para gráficos
        $datosPrimarios = $this->extraerResultados($hojaResultados, 33, 48);
        $datosGlobales = $this->extraerResultados($hojaResultados, 51, 55);

        // 5. Generar e insertar gráficos
        $graficos = GeneradorGraficosExcel::generarGraficos($datosPrimarios, $datosGlobales);
        $this->insertarGraficos($hojaResultados, $graficos);

        // 6. Colocar la hoja de resultados como la activa
        $spreadsheet->setActiveSheetIndexByName('Resultados');

        // 7. Enviamos archivo temporal
        $tempFile = tempnam(sys_get_temp_dir(), 'test16pf_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        // 8. Borramos las imagenes temporales generadas
        foreach ($graficos as $grafico) {
            if (file_exists($grafico)) unlink($grafico);
        }

        return $tempFile;
    }

    /**
     * @param Worksheet $hojaResultados
     * @param array $graficos
     * @return void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function insertarGraficos(Worksheet $hojaResultados, array $graficos): void
    {
        $dibujoPrimario = new Drawing();
        $dibujoPrimario->setName('Gráfico Primario');
        $dibujoPrimario->setPath($graficos['graficoPrimario']); // Ruta al PNG generado
        $dibujoPrimario->setCoordinates('J32'); // Posición donde aparecerá en la hoja
        $dibujoPrimario->setHeight(350); // Puedes ajustar el tamaño
        $dibujoPrimario->setWorksheet($hojaResultados);

        // Insertar gráfico de dimensiones globales
        $dibujoGlobal = new Drawing();
        $dibujoGlobal->setName('Gráfico Global');
        $dibujoGlobal->setPath($graficos['graficoGlobal']);
        $dibujoGlobal->setCoordinates('J50'); // Otra posición
        $dibujoGlobal->setHeight(150);
        $dibujoGlobal->setWorksheet($hojaResultados);

    }

    /**
     * @param Worksheet $hojaResultados
     * @param int $inicio
     * @param int $fin
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Calculation\Exception
     */
    private function extraerResultados(Worksheet $hojaResultados, int $inicio, int $fin): array
    {
        $datos = [];
        for ($fila = $inicio; $fila <= $fin; $fila++) {
            $clave = $hojaResultados->getCell("B$fila")->getValue();
            $valor = $hojaResultados->getCell("D$fila")->getCalculatedValue();
            if ($clave !== null && !is_null($valor)) {
                $datos[(string)$clave] = floatval($valor);
            }
        }
        return $datos;
    }

    private function insertarDatosPersonales(Worksheet $hoja, Postulacion $postulacion, EvaluacionPersonalidad $evaluacion)
    {
        // Aqui va el nombre de la persona y el genero real
        $persona = $postulacion->user_type === User::class ? $postulacion->user?->empleado : $postulacion->user?->persona;
        $nombreCompleto = $postulacion->user_type === User::class ? Empleado::extraerApellidosNombres($persona) : Postulante::extraerNombresApellidos($persona);

        $hoja->setCellValue('D2', $nombreCompleto);
        $hoja->setCellValue('N2', strtoupper($persona->genero));// M o F según el sexo de la persona
        $hoja->setCellValue('U2', Carbon::parse($evaluacion->fecha_realizacion)->format('Y-m-d')); // La fecha que se hizo la evaluación
    }

    /**
     * @param Worksheet $hoja
     * @param $respuestas
     * @return void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function insertarRespuestasEnExcel(Worksheet $hoja, $respuestas)
    {
        // Rango de búsqueda de la tabla: A3:X18 (Pregunta en la primera fila de cada bloque)
        $inicioFila = 3;
        $finFila = 18;
        $inicioColumna = Coordinate::columnIndexFromString('A'); // 1
        $finColumna = Coordinate::columnIndexFromString('X'); // 24

        foreach ($respuestas as $pregunta => $letra) {
            $valorNumerico = match ($letra) {
                'A' => 1,
                'C' => 3,
                default => 2
            };

            $coordenada = null;

            // Buscar dentro del rango A3:X18 el número de pregunta
            for ($fila = $inicioFila; $fila <= $finFila; $fila++) {
                for ($col = $inicioColumna; $col <= $finColumna; $col += 2) {
                    $celda_valor = $hoja->getCell([$col, $fila])->getValue();
                    if ((string)$celda_valor === (string)$pregunta) {
                        $coordenada = [
                            'col' => $col + 1, // una columna a la derecha
                            'fila' => $fila
                        ];
                        break 2; // salir de ambos bucles
                    }
                }
            }

            if ($coordenada) {
                $hoja->setCellValue([$coordenada['col'], $coordenada['fila']], $valorNumerico);
            }

        }
    }

    /**
     * @throws Exception
     */
    private function cargarPlantilla()
    {
        $plantilla = PlantillaBase::obtenerPlantillaByNombre(Constantes::PLANTILLA_16PF);

        return IOFactory::load(public_path($plantilla->url));
    }

    private function mapearRespuestas(EvaluacionPersonalidad $evaluacion): array
    {
        // Convertimos array indexado de respuestas en array asociativo
        $respuestas = [];
        foreach ($evaluacion->respuestas as $index => $valor) {
            $respuestas[$index + 1] = $valor;
        }

        return $respuestas;
    }
}
