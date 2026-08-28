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
    Schema::create('materia_prima', function (Blueprint $table) {
        $table->id('id_materia');
        $table->string('nombre');
        $table->decimal('stock_actual', 10, 2);
        $table->string('unidad_medida');
        $table->decimal('stock_minimo', 10, 2);
        $table->timestamp('ultima_actualizacion')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materia_prima');
    }
};
