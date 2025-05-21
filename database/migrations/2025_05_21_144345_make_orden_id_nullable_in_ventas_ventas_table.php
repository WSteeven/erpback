<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeOrdenIdNullableInVentasVentasTable extends Migration
{
    public function up()
    {
        Schema::table('ventas_ventas', function (Blueprint $table) {
            $table->unsignedBigInteger('orden_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('ventas_ventas', function (Blueprint $table) {
            $table->unsignedBigInteger('orden_id')->nullable(false)->change();
        });
    }
}

