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
        Schema::table('vacaciones', function (Blueprint $table) {
            $table->unsignedBigInteger('reemplazo_id')->nullable()->after('estado');
            $table->text('funciones')->nullable()->after('estado');

            $table->foreign('reemplazo_id')->references('id')->on('empleados')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vacaciones', function (Blueprint $table) {
            $table->dropColumn(['reemplazo_id','funciones']);
        });
    }
};
