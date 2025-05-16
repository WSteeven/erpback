<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportarIndices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importar:indices';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa los índices Scout de Gasto, Pedido, Tarea, otros modelos, etc.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Importando índices...');

        $this->call('scout:import', ['model' => '\App\Models\Pedido']);
        $this->call('scout:import', ['model' => '\App\Models\Devolucion']);
        $this->call('scout:import', ['model' => '\App\Models\TransaccionBodega']);
        $this->call('scout:import', ['model' => '\App\Models\FondosRotativos\Gasto\Gasto']);
        $this->call('scout:import', ['model' => '\App\Models\FondosRotativos\Saldo\Acreditaciones']);
        $this->call('scout:import', ['model' => '\App\Models\Tarea']);
        $this->call('scout:import', ['model' => '\App\Models\Subtarea']);
        $this->call('scout:import', ['model' => '\App\Models\Empleado']);
        $this->call('scout:import', ['model' => '\App\Models\Ticket']);
        $this->call('scout:import', ['model' => '\App\Models\Tareas\TransferenciaProductoEmpleado']);
        $this->call('scout:import', ['model' => '\App\Models\DetalleProducto']);

        $this->info('Importación completa.');

        return Command::SUCCESS;
    }
}
