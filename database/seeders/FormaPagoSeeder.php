<?php

namespace Database\Seeders;

use App\Models\FormaPago;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormaPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       FormaPago::insert([['nombre'=> 'Efectivo'],
       ['nombre'=> 'Cheque'],
       ['nombre'=> 'Nota de Debito'],
       ]);

    }
}
