<?php

use App\Models\Medico\DiagnosticoFicha;
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
        Schema::create('med_diagnostico_fichas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('diagnostico_id');
            $table->enum('tipo', [DiagnosticoFicha::PRESUNTIVO, DiagnosticoFicha::DEFINITIVO]);
            $table->unsignedBigInteger('diagnosticable_id');
            $table->string('diagnosticable_type');

            $table->timestamps();

            $table->foreign('diagnostico_id')->references('id')->on('med_diagnosticos_cita_medica')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('med_diagnostico_fichas');
    }
};
