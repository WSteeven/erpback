<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('seg_bitacoras', function (Blueprint $table) {
            $table->unsignedBigInteger('protector_id')->nullable()->change();
            $table->unsignedBigInteger('conductor_id')->nullable()->change();
        });
    }
    public function down(): void
    {
        Schema::table('seg_bitacoras', function (Blueprint $table) {
            $table->unsignedBigInteger('protector_id')->nullable(false)->change();
            $table->unsignedBigInteger('conductor_id')->nullable(false)->change();
        });
    }
};
