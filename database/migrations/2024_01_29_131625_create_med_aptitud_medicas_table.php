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
        Schema::create('med_aptitudes_medicas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_aptitud_id');
            $table->text('observacion')->nullable();
            $table->text('limitacion')->nullable();
            $table->unsignedBigInteger('aptitudable_id');
            $table->string('aptitudable_type');
            $table->timestamps();

            $table->foreign('tipo_aptitud_id')->on('med_tipos_aptitudes')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('med_aptitudes_medicas');
    }
};
