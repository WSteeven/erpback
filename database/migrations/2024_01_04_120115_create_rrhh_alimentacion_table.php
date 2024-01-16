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
        Schema::create('rrhh_alimentaciones', function (Blueprint $table) {
            $table->id();
            $table->text('nombre');
            $table->string('mes',7);
            $table->boolean('finalizado')->default(false);
            $table->boolean('es_quincena')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_alimentacion');
    }
};
