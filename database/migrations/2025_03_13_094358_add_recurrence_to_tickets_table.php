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
        Schema::table('tickets', function (Blueprint $table) {
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_frequency')->nullable(); // 'daily', 'weekly', 'monthly'
            $table->time('recurrence_time')->default('08:00:00');
            $table->unsignedBigInteger('parent_ticket_id')->nullable();
            $table->foreign('parent_ticket_id')->references('id')->on('tickets');
            $table->boolean('recurrence_active')->default(true); // Nuevo campo: estado de la recurrencia
            $table->integer('recurrence_day_of_week')->nullable(); // 0-6 (domingo-sábado) para semanal
            $table->integer('recurrence_day_of_month')->nullable(); // 1-31 para mensual
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['parent_ticket_id']);
            $table->dropColumn(['is_recurring', 'recurrence_frequency', 'recurrence_time', 'parent_ticket_id', 'recurrence_active', 'recurrence_day_of_week', 'recurrence_day_of_month']);
        });
    }
};
