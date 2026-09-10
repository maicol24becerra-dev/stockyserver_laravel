<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            // Modificar la columna estado para incluir 'cancelado'
            DB::statement("ALTER TABLE pedido MODIFY COLUMN estado ENUM('pendiente', 'en preparación', 'listo', 'entregado', 'cancelado') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido', function (Blueprint $table) {
            // Revertir al enum original
            DB::statement("ALTER TABLE pedido MODIFY COLUMN estado ENUM('pendiente', 'en preparación', 'listo', 'entregado') NOT NULL");
        });
    }
};