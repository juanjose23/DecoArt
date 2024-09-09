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
        Schema::disableForeignKeyConstraints();

        Schema::create('detalle_productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique();
            $table->foreignId('producto_id')->constrained('productos'); // Asegúrate de que esta tabla existe
            $table->foreignId('color_id')->constrained('colors'); // Especificar la tabla correcta
            $table->foreignId('marca_id')->constrained('marcas'); // Especificar la tabla correcta
            $table->foreignId('material_id')->constrained('materials'); // Especificar la tabla correcta
            $table->integer('estado')->default(1);
            $table->timestamps();
            $table->unique(['producto_id', 'color_id', 'marca_id', 'material_id'], 'unique_producto_color_marca_material');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_productos');
    }
};
