<?php

use App\Models\SSO\Inspeccion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{// reporte
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sso_inspecciones', function (Blueprint $table) {
            $table->id();

            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->enum('estado', [Inspeccion::CREADO, Inspeccion::FINALIZADO]);
            $table->longText('seguimiento')->nullable();
            $table->unsignedBigInteger('responsable_id');
            $table->unsignedBigInteger('empleado_involucrado_id')->nullable();
            $table->string('coordenadas')->nullable();
            $table->boolean('tiene_incidencias')->default(false);

            // Foreign keys
            $table->foreign('responsable_id')->references('id')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('empleado_involucrado_id')->references('id')->on('empleados')->nullOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('sso_inspecciones');
    }
};
