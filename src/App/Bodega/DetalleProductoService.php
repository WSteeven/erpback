<?php

namespace Src\App\Bodega;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DetalleProductoService
{
    public function __construct()
    {
    }

    /**
     * @throws Exception
     */
    public static function obtenerSerialesExcel(UploadedFile $archivo)
    {
        $seriales = [];
        try {
            $spreadsheet = IOFactory::load($archivo->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();

            for($row = 2; $row <=$worksheet->getHighestRow(); $row++) {
                $valor = trim($worksheet->getCell('A' . $row)->getValue() ?? '');
                if($valor== '') continue;

                $seriales[] = $valor;
            }
            return $seriales;
        }catch (Exception $e){
            Log::channel('testing')->error('Log', [$e->getLine(), 'DetalleProductoService->obtenerSerialesExcel', $e->getMessage()]);
            throw $e;
        }
    }

}
