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

        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 50);
            $table->string('nombre', 50)->unique();
            $table->string('descripcion', 120);
            $table->boolean('caducidad');
            $table->foreignId('subcategoria_id')->constrained();
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
        Schema::dropIfExists('productos');
    }
};
