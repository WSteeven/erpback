<?php

use App\Models\MovilizacionSubtarea;
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
        Schema::create('movilizacion_subtarea', function (Blueprint $table) {
            $table->id();

            $table->enum('motivo', [MovilizacionSubtarea::IDA_A_TRABAJO, MovilizacionSubtarea::REGRESO_DE_TRABAJO]);
            $table->timestamp('fecha_hora_salida')->nullable();
            $table->timestamp('fecha_hora_llegada')->nullable();
            $table->decimal('latitud', 10, 7);
            $table->decimal('longitud', 10, 7);
            $table->decimal('latitud_llegada', 10, 7);
            $table->decimal('longitud_llegada', 10, 7);

            // Foreign keys
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('subtarea_id');
            $table->foreign('subtarea_id')->references('id')->on('subtareas')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('coordinador_registrante_llegada')->nullable();
            $table->foreign('coordinador_registrante_llegada')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movilizacion_subtarea');
    }
};
