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
    Schema::create('item_pedido', function (Blueprint $table) {
        $table->id('id_item_pedido');
        $table->integer('cantidad');
        $table->decimal('precio_unitario', 10, 2);
        $table->unsignedBigInteger('id_pedido');
        $table->foreign('id_pedido')->references('id_pedido')->on('pedido')->onDelete('cascade');
        $table->unsignedBigInteger('id_plato');
        $table->foreign('id_plato')->references('id_plato')->on('plato')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_pedido');
    }
};
