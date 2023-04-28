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
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->unsignedBigInteger('notificable_id');
            $table->string('notificable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes_finales', function (Blueprint $table) {
            $table->dropColumn('notificable_id');
            $table->dropColumn('notificable_type');
        });
    }
};
