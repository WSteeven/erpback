<?php

namespace App\Imports;

use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSalud;
use App\Models\RecursosHumanos\NominaPrestamos\Familiares;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ExtensionCoverturaSaludImport implements ToModel, WithHeadingRow, WithValidation
{
    public $mes = "";
    private $empleados;
    private $dependientes;
    public function __construct($mes)
    {
        $this->mes = $mes;
        $this->empleados = Empleado::pluck('id', 'identificacion');
        $this->dependientes = Familiares::pluck('id','identificacion');
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new ExtensionCoverturaSalud([
            "mes" => $this->mes,
            "empleado_id" => $this->empleados[$row['cedula']],
            "dependiente" => $this->dependientes[$row['cedula_dependiente']],
            "origen" => $row['origen'],
            "materia_grabada" => $row['materia_grabada'],
            "aporte" => $row['aporte'],
            "aporte_porcentaje" => $row['aporte_porcentaje'],
            "aprobado" => 1,
            "observacion" => $row['observacion'],
        ]);
    }
    public function rules(): array
    {
        return [
            '*.cedula' => ['required'],
            '*.cedula_dependiente' => ['required'],
            '*.origen' => ['string', 'required'],
            '*.materia_grabada' => ['numeric', 'required'],
            '*.aporte' => ['numeric', 'required'],
            '*.aporte_porcentaje' => ['numeric', 'required'],
            '*.observacion' => ['string','nullable'],
        ];
    }
}
