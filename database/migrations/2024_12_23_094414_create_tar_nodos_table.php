<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tar_nodos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coordinador_id');
            $table->text('nombre');
            $table->string('grupos');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('coordinador_id')->references('id')->on('empleados')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tar_nodos');
    }
};
