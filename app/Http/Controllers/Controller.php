<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Barryvdh\DomPDF\Facade\Pdf;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function imprimirSingle($arg)
    {
        // $pdf = \PDF::loadView('ejemplo_pdf');
        $dato = $arg;
        $pdf = Pdf::loadView('ejemplo_pdf', compact('dato'));
        return $pdf->download('singlepdf.pdf');
    }
    public function imprimirMultiple($datos){
        $pdf = Pdf::loadView('ejemplo_plural_pdf', $datos);
        return $pdf->download('multiplepdf.pdf');
    }
}
