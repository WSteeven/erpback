<?php

use App\Models\Inventario;
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
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id'); //fk productos
            $table->unsignedBigInteger('condicion_id'); //fk condicion (nuevo, usado, dañado, etc)
            $table->unsignedBigInteger('ubicacion_id'); //fk ubicaciones (percha-piso donde se encuentra el producto)
            $table->unsignedBigInteger('propietario_id'); //fk propietarios (dueños del productos)

            $table->unsignedInteger('stock')->default(0);
            $table->integer('prestados')->default(0);
            $table->enum('estado',[Inventario::INVENTARIO, Inventario::NODISPONIBLE])->default(Inventario::INVENTARIO);
            $table->timestamps();

            $table->foreign('condicion_id')->references('id')->on('condiciones_de_productos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ubicacion_id')->references('id')->on('ubicaciones')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('propietario_id')->references('id')->on('propietarios')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('inventarios');
    }
};
