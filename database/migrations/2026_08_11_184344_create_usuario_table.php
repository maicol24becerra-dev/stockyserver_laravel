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
    Schema::create('usuario', function (Blueprint $table) {
        $table->id('id_usuario');
        $table->string('nombre');
        $table->string('correo')->unique();
        $table->string('estado');
        $table->string('contrasena');
        $table->string('telefono')->nullable();
        $table->unsignedBigInteger('id_rol');
        $table->foreign('id_rol')->references('id_rol')->on('rol')->onDelete('cascade');
        $table->string('reset_code')->nullable();
        $table->timestamp('reset_expiry')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
