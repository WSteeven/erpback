<?php

namespace App\Imports;

use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoHipotecario;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PrestamoHipotecarioImport implements ToModel, WithHeadingRow, WithValidation
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
        return new PrestamoHipotecario([
            "mes" => $this->mes,
            "empleado_id" => $this->empleados[$row['cedula']],
            "nut" => $row['nut'],
            "valor" => $row['valor']
        ]);
    }
    public function rules(): array
    {
        return [
            '*.cedula' => ['string', 'required'],
            '*.nut' => ['integer', 'required','unique:prestamo_hipotecario,nut'],
            '*.valor' => ['numeric', 'required'],
        ];
    }
}
