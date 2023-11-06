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
        Schema::create('ventas_bono_trimestral_cumplimiento', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendedor_id');
            $table->integer('cant_ventas');
            $table->string('trimestre',7);
            $table->decimal('valor',8,4);
            $table->timestamps();
            $table->foreign('vendedor_id')->references('id')->on('ventas_vendedor')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas_bono_trimestral_cumplimiento');
    }
};
