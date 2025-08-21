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
        Schema::create('adm_cuentas_bancarias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banco_id');
            $table->string('tipo_cuenta');
            $table->string('numero_cuenta')->unique();
            $table->text('observacion')->nullable();
            $table->boolean('es_principal')->default(false);
            $table->timestamps();


            $table->foreign('banco_id')->references('id')->on('bancos')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adm_cuentas_bancarias');
    }
};
