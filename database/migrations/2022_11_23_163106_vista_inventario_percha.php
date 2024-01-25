<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $file_sql = './database/views/vista_inventario_percha.sql';
        // Log::channel('testing')->info('Log', ['ruta del archivo', $file_sql]);
        $sql = file_get_contents($file_sql);
        // Log::channel('testing')->info('Log', ['archivo sql', $sql, ]);
        DB::unprepared($sql);
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('view_inventario_percha');
    }
};
