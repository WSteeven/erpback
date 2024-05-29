<?php

use App\Models\Medico\AccidenteEnfermedadLaboral;
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
        Schema::create('med_accidentes_enfermedades_laborales', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', [AccidenteEnfermedadLaboral::ACCIDENTE_TRABAJO, AccidenteEnfermedadLaboral::ENFERMEDAD_PROFESIONAL]);
            $table->text('observacion')->nullable();
            $table->boolean('calificado_iss');
            $table->text('instituto_seguridad_social')->nullable();
            $table->date('fecha')->nullable();
            $table->unsignedBigInteger('accidentable_id');
            $table->string('accidentable_type');
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
        Schema::dropIfExists('med_accidentes_enfermedades_laborales');
    }
};
