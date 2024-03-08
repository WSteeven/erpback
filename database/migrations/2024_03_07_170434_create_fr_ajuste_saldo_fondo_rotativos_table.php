<?php

use App\Models\FondosRotativos\AjusteSaldoFondoRotativo;
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
        Schema::create('fr_ajuste_saldo_fondo_rotativos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitante_id');
            $table->unsignedBigInteger('destinatario_id');
            $table->unsignedBigInteger('autorizador_id');
            $table->text('motivo');
            $table->text('descripcion');
            $table->double('monto');
            $table->enum('tipo', [AjusteSaldoFondoRotativo::INGRESO, AjusteSaldoFondoRotativo::EGRESO])->nullable();

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
        Schema::dropIfExists('fr_ajuste_saldo_fondo_rotativos');
    }
};
