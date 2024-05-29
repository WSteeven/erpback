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
<<<<<<<< HEAD:database/migrations/2023_11_17_103903_add_campos_to_vehiculos_table.php
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->boolean('estado')->default(true)->after('combustible_id');
========
        Schema::create('med_planificacion_familiars', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
>>>>>>>> origin/desarrollo:database/migrations/2024_04_16_164300_create_med_planificacion_familiars_table.php
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
<<<<<<<< HEAD:database/migrations/2023_11_17_103903_add_campos_to_vehiculos_table.php
        Schema::table('vehiculos', function (Blueprint $table) {
            //
        });
========
        Schema::dropIfExists('med_planificacion_familiars');
>>>>>>>> origin/desarrollo:database/migrations/2024_04_16_164300_create_med_planificacion_familiars_table.php
    }
};
