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
        Schema::create('med_revisiones_actuales_organos_sistemas', function (Blueprint $table) {
            $table->id();

            //ForeingKey
            $table->unsignedBigInteger('organo_sistema_id');
            $table->foreign('organo_sistema_id', 'fk_organos_sistema')->on('med_sistemas_organicos')->references('id')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('ficha_preocupacional_id');
            $table->foreign('ficha_preocupacional_id', 'fk_preocupacional')->on('med_fichas_preocupacionales')->references('id')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_revisiones_actuales_organos_sistemas');
    }
};
