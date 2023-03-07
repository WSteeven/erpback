<?php

namespace Src\App\FondosRotativos;


use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ReportePdfExcelService
{
    public function __construct()
    {
    }

    public  function imprimir_reporte($tipo_ARCHIVO,$tamanio_pagina,$orientacion_pagina, $reportes, $nombre_reporte, $vista,object $export_excel){
        switch ($tipo_ARCHIVO) {
            case 'excel':
                return Excel::download($export_excel, $nombre_reporte . '.xlsx');
                break;
            case 'pdf':
                $pdf = Pdf::loadView($vista, $reportes);
                $pdf->setPaper($tamanio_pagina, $orientacion_pagina);
                return $pdf->download($nombre_reporte . '.pdf');
                break;
        }
    }
}
