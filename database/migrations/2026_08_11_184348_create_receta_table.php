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
    Schema::create('receta', function (Blueprint $table) {
        $table->id('id_receta');
        $table->unsignedBigInteger('id_plato');
        $table->foreign('id_plato')->references('id_plato')->on('plato')->onDelete('cascade');
        $table->unsignedBigInteger('id_materia');
        $table->foreign('id_materia')->references('id_materia')->on('materia_prima')->onDelete('cascade');
        $table->decimal('cantidad_requerida', 10, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receta');
    }
};
