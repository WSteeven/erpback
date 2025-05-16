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
        Schema::create('med_examenes_realizados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('examen_id')->nullable();
            $table->string('tiempo');
            $table->text('resultado');
            $table->unsignedBigInteger('ficha_preocupacional_id')->nullable();
            $table->timestamps();

            $table->foreign('examen_id')->references('id')->on('med_examenes_organos_reproductivos')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('ficha_preocupacional_id')->references('id')->on('med_fichas_preocupacionales')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('med_examenes_realizados');
    }
};
