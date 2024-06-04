<?php

use App\Models\Vehiculos\Vehiculo;
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
            $table->enum('tipo', [Vehiculo::PROPIO, Vehiculo::ALQUILADO])->after('id')->default(Vehiculo::PROPIO);
            $table->boolean('tiene_rastreo')->after('tiene_gravamen')->default(false);
            $table->string('propietario')->after('traccion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'tiene_rastreo', 'propietario']);
        });
    }
};
