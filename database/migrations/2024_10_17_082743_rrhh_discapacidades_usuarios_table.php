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
        Schema::create('rrhh_discapacidades_usuarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_type');
            $table->decimal('porcentaje');
            $table->unsignedBigInteger('tipo_discapacidad_id');

            $table->timestamps();
            $table->foreign('tipo_discapacidad_id', 'fk_tipo_discapacidad')->references('id')->on('rrhh_tipos_discapacidades')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_discapacidades_usuarios');
    }
};
