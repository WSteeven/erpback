<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('detalles_productos', function (Blueprint $table) {
            // Nuevos campos para la tabla detalles_productos: (lote, calibre, peso, dimensiones, permiso, caducidad)
            $table->string('lote')->nullable(); // Assuming lote is a batch number
            $table->string('calibre')->nullable(); // Assuming calibre refers to the caliber or size category
            $table->decimal('peso', 8, 2)->nullable(); // Assuming peso is weight, using decimal for precision (e.g., kg)
            $table->string('dimensiones')->nullable(); // Assuming dimensions in a specific format (e.g., "10x20x30 cm")
            $table->string('permiso')->nullable(); // Assuming permiso is a permit or license code
            $table->date('caducidad')->nullable(); // Assuming caducidad is the expiration date
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detalles_productos', function (Blueprint $table) {
            $table->dropColumn(['lote', 'calibre', 'peso', 'dimensiones', 'permiso', 'caducidad']);
        });
        
    }
};
