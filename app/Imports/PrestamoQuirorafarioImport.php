<?php

namespace App\Imports;

use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoQuirorafario;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PrestamoQuirorafarioImport implements ToModel, WithHeadingRow, WithValidation
{
    public $mes = "";
    private $empleados;
    public function __construct($mes)
    {
        $this->mes = $mes;
        $this->empleados = Empleado::pluck('id', 'identificacion');
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        Log::channel('testing')->info('Log', ['array', $row['cedula']]);

        return new PrestamoQuirorafario([
            "mes" => $this->mes,
            "empleado_id" => $this->empleados[$row['cedula']],
            "nut" => $row['nut'],
            "valor" => $row['valor']
        ]);
    }
    public function rules(): array
    {
        return [
            '*.cedula' => ['required'],
            '*.nut' => ['integer', 'required','unique:prestamo_quirorafario,nut'],
            '*.valor' => ['numeric', 'required'],
        ];
    }
}
