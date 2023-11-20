<?php

namespace Database\Seeders;

use App\Models\Vehiculos\Servicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Servicio::insert([
            ['nombre' => 'CAMBIO DE ACEITE MOTOR', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 5000],
            ['nombre' => 'CAMBIO DE FILTRO ACEITE', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 5000],
            ['nombre' => 'ABS DE FRENOS ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000],
            ['nombre' => 'CAMBIO DE PASTILLAS DE FRENOS ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000],
            ['nombre' => 'CAMBIO DE FILTRO AIRE ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000],
            ['nombre' => 'CAMBIO DE FILTRO DE COMBUSTIBLE', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000],
            ['nombre' => 'MANTENIMIENTO DE INYECTORES ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 20000],
            ['nombre' => 'CONTROL NIVELES DE LIQUIDOS ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 5000],
            ['nombre' => 'CONTROL DE PRESION DE NEUMATICbOS', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 5000],
            ['nombre' => 'CONTROL DE LUCES', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 5000],
            ['nombre' => 'ALINEACION ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000],
            ['nombre' => 'BALANCEO ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000],
            ['nombre' => 'ROTACION ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000],
            ['nombre' => 'ABC MOTOR', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000],
            ['nombre' => 'REAJUSTE GENERAL ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000],
            ['nombre' => 'CAMBIO BANDA DE DISTRIBUCCION', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 80000],
            ['nombre' => 'CAMBIO ACEITE CAJA Y DIFERENCIAL', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 30000],
            ['nombre' => 'CAMBIO LIQUIDO DE FRENOS ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 20000],
            ['nombre' => 'CAMBIO ACEITE HIDRAULICO ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 50000],
            ['nombre' => 'CAMBIO DE BANDA DE SERVICIO', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 70000],
            ['nombre' => 'LAVADA COMPLETA ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000],
        ]);

        Servicio::insert([
            ['nombre' => 'CAMBIO DE BOMBILLO DE LUZ ', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'CAMBIO DE MOÑO DEL ARO ', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'D.Y.M REPARACION TRANSMISION (CAJA DE CAMBIOS)', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'CHEQUEO FRENO CUATRO RUEDAS', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'CHEQUEO SUSPENSIÓN DELANTERA', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'A.B.C DEL MOTOR', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'CAMBIO RULIMAN DE MANZANA DELANTERO ', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'LIMPIEZA INYECTORES', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'BUJIA KIA CERATO 5/8', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'RULIMAN MANZANA', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'AMORTIGUADORES DELANTEROS ', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'HORQUILLAS ', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'SINCRONIZADOS 3ERA', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'CUBO SELECTRO 3ERA', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'RULIMAN ', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'SILICON GRIS PERMATEX', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'KIT MICROFILTROS', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'LIMPIADOR  CARBURADOR ', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'LIMPIADOR  DE FRENOS 600 ML', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'PASTILLAS DE FRENO ', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'RULIMAN ', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'RULIMAN DE CAJA ', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'LITRO DE ACEITE', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'GASOLINA', 'tipo' => Servicio::CORRECTIVO],
            ['nombre' => 'MATERIALES ', 'tipo' => Servicio::CORRECTIVO],
        ]);
    }
}
