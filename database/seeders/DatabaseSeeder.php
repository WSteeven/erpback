<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\ImagenesProducto;
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
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CondicionesProductosFacturasSeeder::class);
        $this->call(ProvinciaSeeder::class);
        $this->call(CantonSeeder::class);
        $this->call(ParroquiaSeeder::class);
        $this->call(AutorizacionSeeder::class);
        $this->call(CategoriaSeeder::class);
        $this->call(MarcaSeeder::class);
        $this->call(NombreProductoSeeder::class);
        $this->call(HiloSeeder::class);
        $this->call(ProductosSeeder::class);
        $this->call(PerchaUbicacionPropietarioSeeder::class);
        $this->call(TiposTransaccionSeeder::class);
        $this->call(ClienteProveedorSeeder::class);
        ImagenesProducto::factory(10)->create();

        // Módulo de Tareas
        $this->call(TareaSeeder::class);
        $this->call(TipoTareaSeeder::class);
        $this->call(SubtareaSeeder::class);
        $this->call(TipoElementoSeeder::class);
    }
}
