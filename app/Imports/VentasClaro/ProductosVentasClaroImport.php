<?php

namespace App\Imports\VentasClaro;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Str;

class ProductosVentasClaroImport implements ToModel
{
    private  string $nombre_archivo;
    private  int $filas_validas;

    public function __construct($nombre_archivo)
    {
        $this->filas_validas = 0;

        $this->nombre_archivo = Str::beforeLast($nombre_archivo, '.');

    }

    public function model(array $row){
        $this->filas_validas++;

    }
}
