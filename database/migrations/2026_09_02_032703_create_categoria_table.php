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
        Schema::create('categoria', function (Blueprint $table) {
            $table->id('id_categoria');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->string('icono', 50)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Modificar tabla plato para usar foreign key
        Schema::table('plato', function (Blueprint $table) {
            // Cambiar categoria a nullable primero
            $table->string('categoria', 50)->nullable()->change();
            // Agregar nueva columna id_categoria
            $table->unsignedBigInteger('id_categoria')->nullable()->after('categoria');
            $table->foreign('id_categoria')->references('id_categoria')->on('categoria')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plato', function (Blueprint $table) {
            $table->dropForeign(['id_categoria']);
            $table->dropColumn('id_categoria');
        });

        Schema::dropIfExists('categoria');
    }
};
