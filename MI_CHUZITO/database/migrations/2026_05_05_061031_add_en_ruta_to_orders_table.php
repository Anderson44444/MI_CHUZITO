<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['en_proceso', 'en_ruta', 'entregado', 'cancelado'])->default('en_proceso')->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['en_proceso', 'entregado', 'cancelado'])->default('en_proceso')->change();
        });
    }
};
