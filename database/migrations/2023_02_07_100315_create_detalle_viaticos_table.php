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
        Schema::create('detalle_viatico', function (Blueprint $table) {
            $table->integer('id')->increment();
            $table->string('descripcion', 250);
            $table->string('autorizacion', 2);
            $table->integer('id_estatus', 12);
            $table->string('transcriptor', 120);
            $table->timestamp('fecha_trans');
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
        Schema::dropIfExists('detalle_viatico');
    }
};
