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
        Schema::create('calificacion_departamento_proveedor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detalle_departamento_id')->nullable();
            $table->unsignedBigInteger('criterio_calificacion_id')->nullable();
            $table->text('comentario')->nullable();
            $table->integer('peso');
            $table->integer('puntaje');
            $table->double('calificacion');
            $table->timestamps();

            $table->foreign('detalle_departamento_id', 'fk_detalle_dept')->references('id')->on('detalle_departamento_proveedor')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('criterio_calificacion_id','fk_criterio_cal')->references('id')->on('criterios_calificaciones')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calificacion_departamento_proveedor');
    }
};
