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
            $table->foreignId('producto_id')->constrained('productos'); // Asegúrate de que esta tabla existe
            $table->foreignId('color_id')->constrained('colors'); // Especificar la tabla correcta
            $table->foreignId('marca_id')->constrained('marcas'); // Especificar la tabla correcta
            $table->foreignId('material_id')->constrained('materials'); // Especificar la tabla correcta
            $table->integer('estado');
            $table->timestamps();
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
