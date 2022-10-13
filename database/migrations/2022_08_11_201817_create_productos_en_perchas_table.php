<?php

use App\Models\Inventario;
use App\Models\ProductoEnPercha;
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
        Schema::create('productos_en_perchas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedBigInteger('ubicacion_id'); //fk ubicaciones (percha-piso donde se encuentra el producto)
            $table->unsignedBigInteger('inventario_id'); //fk inventarios (producto del inventario)

            $table->timestamps();

            $table->unique(['ubicacion_id', 'inventario_id']);
            $table->foreign('ubicacion_id')->references('id')->on('ubicaciones')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('inventario_id')->references('id')->on('inventarios')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos_en_perchas');
    }
};
