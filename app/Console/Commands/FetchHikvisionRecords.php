<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Src\App\RecursosHumanos\ControlPersonal\AsistenciaService;

class FetchHikvisionRecords extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hikvision:fetch-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch daily access records from Hikvision';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(AsistenciaService $service)
    {
        try {
            $records = $service->obtenerRegistrosDiarios24Mayo();


            Log::channel('testing')->info('Log', ['Registros obtenidos en 24Mayo', $records]);

            $this->info('Access records fetched successfully.');
        }catch (\Exception $exception){
            $this->error("Failed to fetch access records: " . $exception->getMessage());
        }

        return Command::SUCCESS;
    }
}
