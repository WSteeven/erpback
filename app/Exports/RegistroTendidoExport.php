<?php

namespace App\Exports;

use App\Models\RegistroTendido;
use Maatwebsite\Excel\Concerns\FromCollection;

class RegistroTendidoExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return RegistroTendido::all();
    }
}
