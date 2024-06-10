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
        Schema::create('med_actividades_fisicas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_actividad');
            $table->text('tiempo');

            // Foreign keys
            $table->unsignedBigInteger('actividable_id');
            $table->string('actividable_type');

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
        Schema::dropIfExists('med_actividades_fisicas');
    }
};
