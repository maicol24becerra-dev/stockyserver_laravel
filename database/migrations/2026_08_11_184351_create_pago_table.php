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
    Schema::create('pago', function (Blueprint $table) {
        $table->id('id_pago');
        $table->decimal('monto_pagado', 10, 2);
        $table->string('metodo_pago');
        $table->dateTime('fecha_pago');
        $table->unsignedBigInteger('id_pedido');
        $table->foreign('id_pedido')->references('id_pedido')->on('pedido')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago');
    }
};
