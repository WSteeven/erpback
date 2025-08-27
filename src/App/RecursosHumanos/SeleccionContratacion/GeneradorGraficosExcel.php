<?php

namespace Src\App\RecursosHumanos\SeleccionContratacion;

use BarPlot;
use Exception;
use Graph;
use mitoteam\jpgraph\MtJpGraph;

class GeneradorGraficosExcel
{
    public function __construct()
    {
    }

    /**
     * @throws Exception
     */
    public static function generarGraficos(array $primarios, array $globales): array
    {
        $rutaPrimario = tempnam(sys_get_temp_dir(), 'grafico_primario_').'.png';
        $rutaGlobal = tempnam(sys_get_temp_dir(), 'grafico_global_' ).'.png';


        self::graficoBarrasHorizontales($primarios, $rutaPrimario, 700, 700);
        self::graficoBarrasHorizontales($globales, $rutaGlobal, 400, 180);

        return [
            'graficoPrimario' => $rutaPrimario,
            'graficoGlobal' => $rutaGlobal,
        ];
    }

    /**
     * @throws Exception
     */
    private static function graficoBarrasHorizontales(array $datos, string $ruta, int $ancho, int $alto)
    {
        // Cargar módulos necesarios
        MtJpGraph::load(['bar'], true);

        $valores = array_values($datos);
        $etiquetas = array_keys($datos);

        $grafico = new Graph($ancho, $alto);
        $grafico->SetShadow();
        $grafico->SetScale("textlin");
        $grafico->Set90AndMargin(100, 30, 20, 40); // Gráfico horizontal

        $grafico->xaxis->SetTickLabels($etiquetas);
        $grafico->xaxis->SetFont(FF_FONT1, FS_NORMAL);

        $grafico->yaxis->HideLine(true);
        $grafico->yaxis->HideTicks(true, true);

//        $grafico->yaxis->scale->SetAutoMin($min);   // mínimo fijo
//        $grafico->yaxis->scale->SetAutoMax($max);  // máximo fijo
        $grafico->yaxis->SetLabelFormat('%d');
        $grafico->yaxis->SetTextLabelInterval(1); // fuerza enteros si es necesario

        $barplot = new BarPlot($valores);
        $barplot->SetFillColor('skyblue');

        // Mostrar los valores en las barras
        $barplot->value->Show();
        $barplot->value->SetFormat('%d');
        $barplot->value->SetColor('black');
        $barplot->value->SetFont(FF_ARIAL, FS_BOLD);
        $barplot->value->SetAngle(0); // En horizontal
//        $barplot->value->SetAlign('left', 'center'); // Alineación correcta
        $barplot->value->SetAlign('center', 'center');


        $grafico->Add($barplot);
        $grafico->Stroke($ruta);
    }
}
