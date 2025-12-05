<?php

namespace App\Console\Commands;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Src\App\RecursosHumanos\ControlPersonal\AsistenciaService;
use Src\App\RecursosHumanos\ControlPersonal\AtrasosService;
use Throwable;

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
     * @param AtrasosService $service
     * @return int
     */
//    public function handle(AsistenciaService $service)
    public function handle(AtrasosService $service)
    {
        try {

            $service->sincronizarAtrasos();


            $this->info('Access records fetched successfully.');
        }catch (Exception $exception){
            $this->error("Failed to fetch access records: " . $exception->getMessage());
        }

        return Command::SUCCESS;
    }
}
