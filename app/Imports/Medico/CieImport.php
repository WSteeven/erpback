<?php

namespace App\Imports\Medico;

use App\Models\Medico\Cie;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CieImport implements ToModel, WithHeadingRow, WithValidation, WithCustomCsvSettings
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new Cie([
            "codigo" => $row['codigo'],
            "nombre_enfermedad" => $row['nombre_enfermedad'],
        ]);
    }
    public function rules(): array
    {
        return [
            '*.codigo' => ['required','unique:med_cies,codigo'],
            '*.nombre_enfermedad' => ['required'],
        ];
    }
    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'ISO-8859-1'
        ];
    }
}
