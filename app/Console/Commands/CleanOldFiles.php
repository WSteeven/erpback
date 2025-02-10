<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use File;
use Illuminate\Console\Command;

class CleanOldFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:old-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eliminar archivos en storage que tengan más de 3 meses';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $storage_path = storage_path();
        $files = File::allFiles($storage_path);


        $deletedCount = 0;
        foreach ($files as $file) {
            if(Carbon::createFromTimestamp($file->getMTime())->lt(Carbon::now()->subMonths(3))) {
                File::delete($file);
                $deletedCount++;
            }
        }
        $this->info('Se eliminaron ' . $deletedCount . ' archivos antiguos');

        return Command::SUCCESS;
    }
}
