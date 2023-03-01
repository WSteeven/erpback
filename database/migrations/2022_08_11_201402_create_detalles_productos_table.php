<?php

use App\Models\DetalleProducto;
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
        Schema::create('detalles_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->string('descripcion')->required();
            $table->unsignedBigInteger('modelo_id');
            $table->string('serial')->unique()->nullable();
            $table->double('precio_compra')->default(0);
            $table->string('color')->nullable();
            $table->string('talla')->nullable();
            $table->enum('tipo', [DetalleProducto::HOMBRE, DetalleProducto::MUJER])->nullable();
            $table->string('url_imagen')->nullable();
            $table->boolean('activo')->default(true);

            $table->timestamps();
            $table->unique(['descripcion', 'serial']);
            $table->foreign('modelo_id')->references('id')->on('modelos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalles_productos');
    }
};
