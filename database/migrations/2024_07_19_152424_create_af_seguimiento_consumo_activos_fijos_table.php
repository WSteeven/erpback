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
        Schema::create('af_seguimiento_consumo_activos_fijos', function (Blueprint $table) {
            $table->id();

            $table->integer('cantidad_utilizada');
            $table->unsignedBigInteger('motivo_consumo_activo_fijo_id');
            $table->unsignedBigInteger('activo_fijo_id');

            // Foreign keys
            $table->foreign('motivo_consumo_activo_fijo_id', 'fk_seguimiento_consumo_motivo_consumo')->references('id')->on('af_motivos_consumo_activos_fijos')->onDeleteCascade()->onUpdateCascade();
            $table->foreign('activo_fijo_id')->references('id')->on('af_activos_fijos')->onDeleteCascade()->onUpdateCascade();

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
        Schema::dropIfExists('af_seguimiento_consumo_activos_fijos');
    }
};
