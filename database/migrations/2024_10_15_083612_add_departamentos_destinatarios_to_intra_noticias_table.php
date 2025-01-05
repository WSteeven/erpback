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
        Schema::table('intra_noticias', function (Blueprint $table) {
            $table->text('departamentos_destinatarios')->nullable(); // Agrega el nuevo campo
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('intra_noticias', function (Blueprint $table) {
            $table->dropColumn('departamentos_destinatarios'); // Elimina el campo si se deshace la migración
        });
    }
};
