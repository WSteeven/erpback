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
        Schema::create('actividad_realizadas', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fecha_hora');
            $table->text('actividad_realizada');
            $table->string('observacion')->nullable();
            $table->string('fotografia')->nullable();
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('actividable_id');
            $table->text('actividable_type');

            $table->timestamps();
            $table->foreign('empleado_id')->references('id')->on('empleados')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actividad_realizadas');
    }
};
