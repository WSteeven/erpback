<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanTemporaryImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:temp-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia la carpeta de imágenes temporales usadas en Excel';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Storage::deleteDirectory('public/temp_images');
        Storage::makeDirectory('public/temp_images');
        $this->info('Carpeta de imagenes temporales limpiada');

        return Command::SUCCESS;
    }
}
