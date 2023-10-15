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
        Schema::create('ventas_producto_ventas', function (Blueprint $table) {
            $table->id();
            $table->string('bundle_id');
            $table->string('precio');
            $table->unsignedBigInteger('plan_id');
            $table->timestamps();
            $table->foreign('plan_id', 'fk_plan_id')->references('id')->on('ventas_planes')->cascadeOnUpdate();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas_producto_ventas');
    }
};
