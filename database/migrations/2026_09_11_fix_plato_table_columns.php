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
        Schema::table('plato', function (Blueprint $table) {
            // Cambiar tipo de disponibilidad de string a integer
            $table->integer('disponibilidad')->default(1)->change();
            
            // Hacer descripcion nullable (puede no ser requerida)
            $table->text('descripcion')->nullable()->change();
            
            // Hacer categoria nullable
            $table->string('categoria')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plato', function (Blueprint $table) {
            $table->string('disponibilidad')->change();
            $table->text('descripcion')->change();
            $table->string('categoria')->change();
        });
    }
};
