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
            $table->unsignedBigInteger('organo_id');
            $table->text('descripcion');
            $table->unsignedBigInteger('revisionable_id');
            $table->string('revisionable_type');
            
            $table->timestamps();
            $table->foreign('organo_id')->on('med_sistemas_organicos')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
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
