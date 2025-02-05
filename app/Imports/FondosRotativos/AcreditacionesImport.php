<?php

namespace App\Imports\FondosRotativos;

use App\Models\Empleado;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Str;

class AcreditacionesImport implements ToModel
{
    private  string $nombre_archivo;
    private  int $filas_validas;
    private string $fecha_acreditacion;
    public function __construct($nombre_archivo)
    {
        $this->filas_validas = 0;
//        $this->nombre_archivo = str_replace(['.xlsx','.xls'], ['',''],$nombre_archivo);
        $this->nombre_archivo = Str::beforeLast($nombre_archivo, '.');
    }

    /**
     * @param array $row
     */
    public function model(array $row)
    {
        $this->filas_validas++;
        $cant_nulos = count(array_filter($row, fn($valor) => is_null($valor)));
//         Log::channel('testing')->info('Log', ["Fila en import ($this->filas_validas)"." nulos: (".count(array_filter($row, fn($valor) => is_null($valor))).")", $row]);

        if($this->filas_validas === 8) $this->fecha_acreditacion =  $this->castearFecha($row[6]);
        // El calculo se hace a partir de la fila 12 que es la primera fila válida, cada fila a partir de aquí,
        // incluyendo esta, indica que es un registro de acreditacion que se va a crear
        if($this->filas_validas >= 12 && $cant_nulos<10){
            Acreditaciones::create([
                'id_tipo_fondo'=>1, //siempre se guarda como individual
                'id_tipo_saldo'=>1, //siempre es transferencia, ya que se usa esta opcion de subir cash
                'id_usuario'=>Empleado::where('identificacion', $row[9])->first()->id,
                'id_saldo'=> $row[19],
                'fecha'=>$this->fecha_acreditacion,
                'descripcion_acreditacion'=>$this->nombre_archivo,
                'monto'=>$row[11],
                'id_estado'=>1,
            ]);
        }
    }

    function castearFecha($excelFecha)
    {
        $fechaConvertida = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($excelFecha - 2);

        return $fechaConvertida->format('Y-m-d'); // Resultado en formato: "2025-01-17"
    }
}
