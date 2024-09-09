<?php

namespace Database\Seeders\Vehiculos;

use App\Models\Vehiculos\Servicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Preventivos
        Servicio::firstOrCreate(['nombre' => 'CAMBIO DE ACEITE MOTOR', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 5000]);
        Servicio::firstOrCreate(['nombre' => 'CAMBIO DE FILTRO ACEITE', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 5000]);
        Servicio::firstOrCreate(['nombre' => 'ABS DE FRENOS ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000]);
        Servicio::firstOrCreate(['nombre' => 'CAMBIO DE PASTILLAS DE FRENOS ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000]);
        Servicio::firstOrCreate(['nombre' => 'CAMBIO DE FILTRO AIRE ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000]);
        Servicio::firstOrCreate(['nombre' => 'CAMBIO DE FILTRO DE COMBUSTIBLE', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000]);
        Servicio::firstOrCreate(['nombre' => 'MANTENIMIENTO DE INYECTORES ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 20000]);
        Servicio::firstOrCreate(['nombre' => 'CONTROL NIVELES DE LIQUIDOS ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 5000]);
        Servicio::firstOrCreate(['nombre' => 'CONTROL DE PRESION DE NEUMATICbOS', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 5000]);
        Servicio::firstOrCreate(['nombre' => 'CONTROL DE LUCES', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 5000]);
        Servicio::firstOrCreate(['nombre' => 'ALINEACION ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000]);
        Servicio::firstOrCreate(['nombre' => 'BALANCEO ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000]);
        Servicio::firstOrCreate(['nombre' => 'ROTACION ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000]);
        Servicio::firstOrCreate(['nombre' => 'ABC MOTOR', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000]);
        Servicio::firstOrCreate(['nombre' => 'REAJUSTE GENERAL ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000]);
        Servicio::firstOrCreate(['nombre' => 'CAMBIO BANDA DE DISTRIBUCCION', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 80000]);
        Servicio::firstOrCreate(['nombre' => 'CAMBIO ACEITE CAJA Y DIFERENCIAL', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 30000]);
        Servicio::firstOrCreate(['nombre' => 'CAMBIO LIQUIDO DE FRENOS ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 20000]);
        Servicio::firstOrCreate(['nombre' => 'CAMBIO ACEITE HIDRAULICO ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 50000]);
        Servicio::firstOrCreate(['nombre' => 'CAMBIO DE BANDA DE SERVICIO', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 70000]);
        Servicio::firstOrCreate(['nombre' => 'LAVADA COMPLETA ', 'tipo' => Servicio::PREVENTIVO, 'intervalo' => 10000]);


        // Correctivos
        Servicio::firstOrCreate(['nombre' => 'CAMBIO DE BOMBILLO DE LUZ ', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'CAMBIO DE MOÑO DEL ARO ', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'D.Y.M REPARACION TRANSMISION (CAJA DE CAMBIOS)', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'CHEQUEO FRENO CUATRO RUEDAS', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'CHEQUEO SUSPENSIÓN DELANTERA', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'A.B.C DEL MOTOR', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'CAMBIO RULIMAN DE MANZANA DELANTERO ', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'LIMPIEZA INYECTORES', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'BUJIA KIA CERATO 5/8', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'RULIMAN MANZANA', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'AMORTIGUADORES DELANTEROS ', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'HORQUILLAS ', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'SINCRONIZADOS 3ERA', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'CUBO SELECTRO 3ERA', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'RULIMAN ', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'SILICON GRIS PERMATEX', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'KIT MICROFILTROS', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'LIMPIADOR  CARBURADOR ', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'LIMPIADOR  DE FRENOS 600 ML', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'PASTILLAS DE FRENO ', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'RULIMAN ', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'RULIMAN DE CAJA ', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'LITRO DE ACEITE', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'GASOLINA', 'tipo' => Servicio::CORRECTIVO]);
        Servicio::firstOrCreate(['nombre' => 'MATERIALES ', 'tipo' => Servicio::CORRECTIVO]);
    }
}
