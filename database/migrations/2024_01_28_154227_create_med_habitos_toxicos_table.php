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
        Schema::create('med_resultados_habitos_toxicos', function (Blueprint $table) {
            $table->id();
            $table->string('tiempo_consumo_meses');
            $table->string('tiempo_abstinencia_meses');
            $table->string('cantidad');
            $table->boolean('ex_consumidor')->default(false);
            $table->unsignedBigInteger('habito_toxicable_id');
            $table->string('habito_toxicable_type');

            $table->unsignedBigInteger('tipo_habito_toxico_id');
            $table->foreign('tipo_habito_toxico_id')->on('med_tipos_habitos_toxicos')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('med_resultados_habitos_toxicos');
    }
};
