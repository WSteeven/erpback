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
        Schema::table('pedidos', function (Blueprint $table){
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->unsignedBigInteger('per_retira_id')->nullable();
            $table->string('evidencia1')->nullable();
            $table->string('evidencia2')->nullable();

            $table->foreign('per_retira_id')->references('id')->on('empleados')->onDelete(null)->cascadeOnUpdate();
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete(null)->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
