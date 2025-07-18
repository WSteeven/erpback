<?php

namespace Database\Seeders\SSO;

use App\Models\SSO\Certificacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CertificacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\SSO\CertificacionSeeder"
     * @return void
     */
    public function run()
    {
        Certificacion::insert([
            ['descripcion' => 'PREVENCIÓN DE RIESGOS LABORALES CONSTRUCCIÓN Y OBRAS PÚBLICAS.'],
            ['descripcion' => 'PREVENCIÓN DE RIESGOS LABORALES ENERGÍA ELÉCTRICA.'],
            ['descripcion' => 'PREVENCIÓN EN TRABAJO DE ALTURAS.'],
            ['descripcion' => 'PREVENCIÓN DE RIESGOS LABORALES.'],
        ]);
    }
}
