<?php

namespace App\Rules;

use App\Models\DetalleProducto;
use Exception;
use Illuminate\Contracts\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ValidarFormatoSerialExcel implements Rule
{
    protected string $message = '';
    protected array $seriales = [];
    public function passes($attribute, $value)
    {
        // $value es el archivo subido (UploadedFile)
        if (!$value->isValid()) {
            $this->message = "El $attribute no es válido.";
            return false;
        }

        try {
            $spreadsheet = IOFactory::load($value->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();

            // Obtener el valor exacto de A1
            $header = trim($worksheet->getCell('A1')->getValue());

            // Normalizamos (quitamos acentos, espacios, mayúsculas)
            $headerNormalizado = mb_strtoupper(preg_replace('/\s+/', '', $header));

            if ($headerNormalizado !== 'SERIAL') {
                $this->message = "La celda A1 del $attribute debe contener exactamente 'serial'. Encontrado: '$header'";
                return false;
            }

            // Contar columnas con datos (ignorando filas vacías)
            $highestColumn = $worksheet->getHighestColumn(); // Ej: "Z"
            $highestRow = $worksheet->getHighestRow();

            $columnasConDatos = 0;
            for ($col = 'A'; $col <= $highestColumn; ++$col) {
                for ($row = 1; $row <= $highestRow; ++$row) {
                    $cellValue = trim($worksheet->getCell($col . $row)->getValue() ?? '');
                    if ($cellValue !== '') {
                        $columnasConDatos++;
                        break; // Ya encontramos algo en esta columna
                    }
                }
            }

            // Solo debe haber 1 columna con datos (la A)
            if ($columnasConDatos > 1) {
                $this->message = 'El archivo Excel debe tener datos únicamente en la columna A (serial). Se detectaron datos en otras columnas.';
                return false;
            }

            // Opcional: validar que haya al menos 1 serial (además del encabezado)
            $serialesEncontrados = 0;
            for ($row = 2; $row <= $highestRow; ++$row) {
                $valor = trim($worksheet->getCell('A' . $row)->getValue() ?? '');
                if ($valor !== '') {
                    $serialesEncontrados++;
                    $this->seriales[] = $valor;
                }
            }

            if ($serialesEncontrados === 0) {
                $this->message = 'El archivo no contiene ningún número de serie (solo el encabezado).';
                return false;
            }

            //Validar seriales duplicados en el archivo
            $duplicadosEnArchivo = collect($this->seriales)->duplicates()->keys()->all();
            if(!empty($duplicadosEnArchivo)){
                $lista = implode(', ', $duplicadosEnArchivo);
                $this->message = "Los siguientes seriales están repetidos en el archivo: $lista";
                return false;
            }

            // Validar que ningun serial ya exista en la BD con la misma descripcion
            $descripcion = request()->input('descripcion');
            if($descripcion === ''){
                $this->message = "No se pudo leer la descripción del producto";
                return false;
            }
            $existenEnBD = DetalleProducto::whereIn('serial', $this->seriales)->where('descripcion', 'LIKE',"%$descripcion%")->pluck('serial')->all();
            if (!empty($existenEnBD)) {
                $lista = implode(', ', $existenEnBD);
                $this->message = "Los siguientes seriales ya están registrados en la base de datos con esta descripción: $lista";
                return false;
            }

        } catch (Exception $e) {
            $this->message = 'No se pudo leer el archivo Excel. Asegúrate de que sea un archivo .xlsx o .xls válido. '. $e->getMessage();
            return false;
        }
        return true;
    }

    public function message()
    {
        return $this->message;
    }

}
