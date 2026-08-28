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
    Schema::create('pedido', function (Blueprint $table) {
        $table->id('id_pedido');
        $table->dateTime('fecha');
        $table->string('estado');
        $table->unsignedBigInteger('id_usuario');
        $table->foreign('id_usuario')->references('id_usuario')->on('usuario')->onDelete('cascade');
        $table->unsignedBigInteger('id_cliente');
        $table->foreign('id_cliente')->references('id_cliente')->on('cliente')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido');
    }
};
