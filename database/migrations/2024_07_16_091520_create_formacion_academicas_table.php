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
        Schema::create('rrhh_contratacion_formaciones_academicas', function (Blueprint $table) {
            $table->id();
            $table->string('nivel');
            $table->string('nombre');
            $table->text('formacionable_type');
            $table->unsignedBigInteger('formacionable_id');
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
        Schema::dropIfExists('rrhh_contratacion_formaciones_academicas');
    }
};
