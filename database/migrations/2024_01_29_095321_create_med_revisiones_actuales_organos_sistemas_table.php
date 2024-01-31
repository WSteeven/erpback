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
            $table->unsignedBigInteger('organo_sistema_id');
            $table->text('descripcion');
            $table->unsignedBigInteger('preocupacional_id');
            $table->foreign('organo_sistema_id')->on('med_sistemas_organicos')->references('id')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('preocupacional_id')->on('med_preocupacionales')->references('id')->nullOnDelete()->cascadeOnUpdate();
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
