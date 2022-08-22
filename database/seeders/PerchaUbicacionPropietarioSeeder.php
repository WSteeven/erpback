<?php

namespace Database\Seeders;

use App\Models\Percha;
use App\Models\Piso;
use App\Models\Propietario;
use App\Models\Ubicacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerchaUbicacionPropietarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Propietario::create(['nombre'=>'JP']);
        Propietario::create(['nombre'=>'TELCONET']);
        Propietario::create(['nombre'=>'ACCESS']);
        Propietario::create(['nombre'=>'NEDETELL']);

        Percha::create([
            'nombre'=>'PA1',
            "sucursal_id"=>1
        ]);
        Percha::create([
            'nombre'=>'PA2',
            "sucursal_id"=>1
        ]);
        Percha::create([
            'nombre'=>'PB1',
            "sucursal_id"=>1
        ]);
        Percha::create([
            'nombre'=>'PB2',
            "sucursal_id"=>1
        ]);


        //Pisos
        Piso::create([
            'fila'=>'F1',
            "columna"=>'C1'
        ]);
        Piso::create([
            'fila'=>'F1',
            "columna"=>'C2'
        ]);
        Piso::create([
            'fila'=>'F1',
            "columna"=>'C3'
        ]);
        Piso::create([
            'fila'=>'F1',
            "columna"=>'C4'
        ]);
        Piso::create([
            'fila'=>'F2',
            "columna"=>'C1'
        ]);
        Piso::create([
            'fila'=>'F2',
            "columna"=>'C2'
        ]);
        Piso::create([
            'fila'=>'F2',
            "columna"=>'C3'
        ]);
        Piso::create([
            'fila'=>'F2',
            "columna"=>'C4'
        ]);

        //Ubicaciones
        Ubicacion::create([
            'codigo'=>'P001F1',
            'percha_id'=>1,
            'piso_id'=>1,
        ]);
        Ubicacion::create([
            'codigo'=>'P001F2',
            'percha_id'=>1,
            'piso_id'=>2,
        ]);
        Ubicacion::create([
            'codigo'=>'P001F3',
            'percha_id'=>1,
            'piso_id'=>3,
        ]);
        Ubicacion::create([
            'codigo'=>'P001F4',
            'percha_id'=>1,
            'piso_id'=>4,
        ]);
        Ubicacion::create([
            'codigo'=>'P001F5',
            'percha_id'=>1,
            'piso_id'=>5,
        ]);
        Ubicacion::create([
            'codigo'=>'P001F6',
            'percha_id'=>1,
            'piso_id'=>6,
        ]);
        Ubicacion::create([
            'codigo'=>'P001F7',
            'percha_id'=>1,
            'piso_id'=>7,
        ]);
        Ubicacion::create([
            'codigo'=>'P001F8',
            'percha_id'=>1,
            'piso_id'=>8,
        ]);
        Ubicacion::create([
            'codigo'=>'P002F1',
            'percha_id'=>2,
            'piso_id'=>1,
        ]);
        Ubicacion::create([
            'codigo'=>'P002F2',
            'percha_id'=>2,
            'piso_id'=>2,
        ]);
        Ubicacion::create([
            'codigo'=>'P002F3',
            'percha_id'=>2,
            'piso_id'=>3,
        ]);
        Ubicacion::create([
            'codigo'=>'P002F4',
            'percha_id'=>2,
            'piso_id'=>4,
        ]);

    }
}
