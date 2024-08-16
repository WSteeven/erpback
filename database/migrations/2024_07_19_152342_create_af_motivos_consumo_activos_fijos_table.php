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
        Schema::create('af_motivos_consumo_activos_fijos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->unsignedBigInteger('categoria_motivo_consumo_activo_fijo_id');

            // Foreign keys
            $table->foreign('categoria_motivo_consumo_activo_fijo_id', 'fk_motivos_consumo_categoria_motivos_consumo')->references('id')->on('af_categorias_motivos_consumo_activos_fijos')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('af_motivos_consumo_activos_fijos');
    }
};
