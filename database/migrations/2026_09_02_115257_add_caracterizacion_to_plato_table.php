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
            // Información nutricional
            $table->integer('calorias')->nullable()->after('descripcion');
            $table->decimal('proteinas', 8, 2)->nullable()->after('calorias');
            $table->decimal('carbohidratos', 8, 2)->nullable()->after('proteinas');
            $table->decimal('grasas', 8, 2)->nullable()->after('carbohidratos');
            
            // Características adicionales
            $table->text('alergenos')->nullable()->after('grasas');
            $table->boolean('vegetariano')->default(false)->after('alergenos');
            $table->boolean('vegano')->default(false)->after('vegetariano');
            $table->boolean('sin_gluten')->default(false)->after('vegano');
            $table->text('ingredientes_principales')->nullable()->after('sin_gluten');
            $table->integer('tiempo_preparacion')->nullable()->after('ingredientes_principales')->comment('En minutos');
            $table->enum('nivel_picante', ['ninguno', 'bajo', 'medio', 'alto'])->default('ninguno')->after('tiempo_preparacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plato', function (Blueprint $table) {
            $table->dropColumn([
                'calorias',
                'proteinas',
                'carbohidratos',
                'grasas',
                'alergenos',
                'vegetariano',
                'vegano',
                'sin_gluten',
                'ingredientes_principales',
                'tiempo_preparacion',
                'nivel_picante'
            ]);
        });
    }
};
