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
        Schema::create('ventas_productos_ventas', function (Blueprint $table) {
            $table->id();
            $table->text('nombre');
            $table->string('bundle_id');
            $table->decimal('precio',8,4);
            $table->unsignedBigInteger('plan_id');
            $table->boolean('activo')->default(true);
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
        Schema::dropIfExists('ventas_productos_ventas');
    }
};
