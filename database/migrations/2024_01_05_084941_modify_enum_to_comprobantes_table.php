<?php

use App\Models\TransaccionBodega;
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
        Schema::table('comprobantes', function (Blueprint $table) {
            $table->string('estado')->default(TransaccionBodega::PENDIENTE)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comprobantes', function (Blueprint $table) {
            $table->enum('estado', [TransaccionBodega::PENDIENTE, TransaccionBodega::ACEPTADA, transaccionBodega::RECHAZADA])->default(TransaccionBodega::PENDIENTE)->change();
        });
    }
};
