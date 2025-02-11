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
        Schema::create('cmp_beneficiarios', function (Blueprint $table) {
            $table->id();

            $table->string('codigo_beneficiario');
            $table->string('tipo_documento');
            $table->string('identificacion_beneficiario');
            $table->string('nombre_beneficiario');
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('localidad')->nullable();

            $table->foreignId('canton_id')->nullable()->constrained('cantones')->onDelete('cascade');

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
        Schema::dropIfExists('cmp_beneficiarios');
    }
};
