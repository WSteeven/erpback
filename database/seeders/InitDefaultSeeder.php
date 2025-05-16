<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InitDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ProvinciaSeeder::class,
            CantonSeeder::class,
            ParroquiaSeeder::class,

            AreasSeeder::class,
            AutorizacionSeeder::class,
            CargoSeeder::class,
            CategoriaSeeder::class,
            CondicionesProductosFacturasSeeder::class,
            DepartamentoSeeder::class,
            DescuentosGeneralesSeeder::class,
            DetalleViaticoSeeder::class,
            EstadoAcreditacionesSeeder::class,
            EsatdoAcreditacionSeeder::class,
            EstadoCivilSeeder::class,
            EstadoPermisoEmpleadoSeeder::class,
            EstadoViaticoSeeder::class,
            EstatusSeeder::class,
            FormaPagoSeeder::class,
            HiloSeeder::class,
            HorasExtrasTipoSeeder::class,
            HorasExtrasSubTipoSeeder::class,
            ModalidadSeeder::class,
            MotivoGastoSeeder::class,
            MotivoPausaSeeder::class,
            MotivoPausaTicketSeeder::class,
            MotivoPermisoEmpleadoSeeder::class,
            TipoTransaccionSeeder::class,
            MotivoSeeder::class,
            MotivoSuspendidoSeeder::class,
            MultaSeeder::class,
            PeriodoSeeder::class,
            ProcesadorSeeder::class,
            RamSeeder::class,
            RoleSeeder::class,
            RubroSeeder::class,
            SpanSeeder::class,
            SubDetalleViaticoSeeder::class,
            TipoContratoSeeder::class,
            TipoElementoSeeder::class,
            TipoFondoSeeder::class,
            TipoSaldoSeeder::class,
            TipoTrabajoSeeder::class,
            UnidadMedidaSeeder::class,

        ]);
    }
}
