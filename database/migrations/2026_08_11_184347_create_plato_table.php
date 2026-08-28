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
    Schema::create('plato', function (Blueprint $table) {
        $table->id('id_plato');
        $table->string('nombre');
        $table->decimal('precio', 10, 2);
        $table->text('descripcion');
        $table->string('disponibilidad');
        $table->string('categoria');
        $table->string('imagen')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plato');
    }
};
