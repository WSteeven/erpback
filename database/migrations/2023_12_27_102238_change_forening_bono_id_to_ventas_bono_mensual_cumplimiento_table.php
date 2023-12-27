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
        Schema::table('ventas_bono_mensual_cumplimiento', function (Blueprint $table) {
            $table->dropForeign('ventas_bono_mensual_cumplimiento_bono_id_foreign');
            $table->string('bono_type')->after('valor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventas_bono_mensual_cumplimiento', function (Blueprint $table) {
            //
        });
    }
};
