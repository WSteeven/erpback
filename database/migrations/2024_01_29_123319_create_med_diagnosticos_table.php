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
        Schema::create('med_diagnosticos', function (Blueprint $table) {
            $table->id();
            $table->text('descripcion');
            $table->string('cie');
            $table->string('presuntivo');
            $table->string('definitivo');
            $table->unsignedBigInteger('preocupacional_id');
            $table->foreign('preocupacional_id')->on('med_preocupacionales')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('med_diagnosticos');
    }
};
