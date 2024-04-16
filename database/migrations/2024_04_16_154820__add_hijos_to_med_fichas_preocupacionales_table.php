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
        Schema::table('med_fichas_preocupacionales', function (Blueprint $table) {
            $table->integer('hijos_vivos')->default(0)->after('enfermedad_actual');
            $table->integer('hijos_muertos')->default(0)->after('enfermedad_actual');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('med_fichas_preocupacionales', function (Blueprint $table) {
            //
        });
    }
};
