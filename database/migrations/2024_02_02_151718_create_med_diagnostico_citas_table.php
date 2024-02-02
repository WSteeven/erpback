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
        Schema::create('med_diagnosticos_citas', function (Blueprint $table) {
            $table->id();
            $table->string('recomendacion');

            //Laves foraneas
            $table->unsignedBigInteger('cie_id');
            $table->foreign('cie_id')->references('id')->on('med_cies')->cascadeOnUpdate();
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
        Schema::dropIfExists('med_diagnostico_citas');
    }
};
