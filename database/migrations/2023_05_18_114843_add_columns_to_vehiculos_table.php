<?php

use App\Models\Vehiculo;
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
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->enum('traccion', [Vehiculo::SENCILLA_DELANTERA, Vehiculo::SENCILLA_TRASERA, Vehiculo::AWD, Vehiculo::FOUR_WD, Vehiculo::TODOTERRENO])->after('rendimiento');
            $table->boolean('aire_acondicionado')->default(false)->after('traccion');
            $table->double('capacidad_tanque', 19, 2, true)->after('aire_acondicionado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('vehiculos', function (Blueprint $table) {
        //     $table->dropColumn(['traccion','aire_acondicionado', 'capacidad_tanque']);
        // });
    }
};
