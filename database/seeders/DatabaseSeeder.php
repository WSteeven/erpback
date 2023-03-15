<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\FondosRotativos\Gasto\DetalleViatico;
use App\Models\FondosRotativos\Gasto\SubDetalleViatico;
use App\Models\ImagenProducto;
use App\Models\TransaccionBodega;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // Modulo de Bodega
        $this->call(GrupoSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(CargoSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CondicionesProductosFacturasSeeder::class);
        $this->call(ProvinciaSeeder::class);
        $this->call(CantonSeeder::class);
        $this->call(ParroquiaSeeder::class);
        $this->call(AutorizacionSeeder::class);
        $this->call(CategoriaSeeder::class);
        $this->call(DiscoSeeder::class);
        $this->call(ProcesadorSeeder::class);
        $this->call(RamSeeder::class);
        $this->call(MarcaSeeder::class);
        $this->call(ClienteProveedorSeeder::class);
        $this->call(UnidadMedidaSeeder::class);
        $this->call(ProductosSeeder::class);
        $this->call(HiloSeeder::class);
        $this->call(SpanSeeder::class);
        $this->call(DetalleProductoSeeder::class);
        $this->call(CodigoClienteSeeder::class);
        $this->call(FibraSeeder::class);
        $this->call(ComputadoraTelefonoSeeder::class);
        $this->call(InventarioSeeder::class);
        $this->call(DevolucionSeeder::class);
        $this->call(PerchaUbicacionPropietarioSeeder::class);
        $this->call(TipoTransaccionSeeder::class);
        $this->call(MotivoSeeder::class);
        // $this->call(PedidoSeeder::class); */

        // Módulo de Tareas
        $this->call(ProyectoSeeder::class);
        $this->call(ClienteFinalSeeder::class);
        $this->call(TareaSeeder::class);
        $this->call(TipoTrabajoSeeder::class);
        //$this->call(SubtareaSeeder::class);
        $this->call(TipoElementoSeeder::class);
        $this->call(PropietarioElementoSeeder::class);
        $this->call(MotivoSuspendidoSeeder::class);
        $this->call(MotivoPausaSeeder::class);
        // $this->call(TrabajoSeeder::class);
        //$this->call(MaterialGrupoTareaSeeder::class);
        //$this->call(ControlMaterialesSubtareasSeeder::class);

        // $this->call(TransaccionBodegaSeeder::class);
        // ImagenProducto::factory(10)->create();
       // $this->call(PedidoSeeder::class);
        $this->call(EstatusSeeder::class);
        $this->call(TipoFondoSeeder::class);
        $this->call(TipoSaldoSeeder::class);
        $this->call(EstadoViaticoSeeder::class);
        $this->call(DetalleViaticoSeeder::class);
        $this->call(SubDetalleViaticoSeeder::class);
        // $this->call(PedidoSeeder::class);
    }
}
