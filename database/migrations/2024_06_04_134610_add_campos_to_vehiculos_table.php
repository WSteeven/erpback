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
            $table->unsignedBigInteger('custodio_id')->nullable()->after('rendimiento');
            $table->string('conductor_externo')->nullable()->after('propietario');
            $table->string('identificacion_conductor_externo')->nullable()->after('propietario');

            $table->foreign('custodio_id')->references('id')->on('empleados')->cascadeOnUpdate()->nullOnDelete();
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
            $table->dropColumn([
                'tipo', 'tiene_rastreo', 'propietario', 'custodio_id',
                'conductor_externo', 'identificacion_conductor_externo'
            ]);
            $table->dropForeign('custodio_id');
        });
    }
};
