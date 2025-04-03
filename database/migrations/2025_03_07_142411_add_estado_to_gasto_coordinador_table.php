<?php

use App\Models\EstadoTransaccion;
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
        Schema::table('gastos_coordinador', function (Blueprint $table) {
            $table->boolean('revisado')->default(false)->after('id_grupo');
            $table->unsignedBigInteger('estado_id')->default(EstadoTransaccion::PENDIENTE_ID)->after('id_grupo');
            $table->text('observacion_contabilidad')->nullable()->after('estado_id');

            $table->foreign('estado_id')->references('id')->on('estados_transacciones_bodega')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gastos_coordinador', function (Blueprint $table) {
            $table->dropForeign(['estado_id']);
            $table->dropColumn(['revisado', 'estado_id', 'observacion_contabilidad']);
        });
    }
};
