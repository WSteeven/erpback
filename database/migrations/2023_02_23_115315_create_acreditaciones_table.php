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
        Schema::create('acreditaciones', function (Blueprint $table) {
            $table->id();
            $table->string('id_saldo', 12);
            $table->string('descripcion_saldo', 1500);
            $table->decimal('monto', $precision = 19, $scale = 2);
            $table->unsignedBigInteger('id_tipo_saldo');
            $table->unsignedBigInteger('id_tipo_fondo');
            $table ->foreign('id_tipo_saldo')->references('id')->on('tipo_saldo');
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
        Schema::dropIfExists('acreditaciones');
    }
};
