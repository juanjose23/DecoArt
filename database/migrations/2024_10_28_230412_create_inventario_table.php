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
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // Código único del lote
            $table->boolean('es_caducable')->default(false); 
            $table->date('fecha_expiracion')->nullable(); // Fecha de expiración del lote
         
            $table->integer('estado'); // Estado del lote (ej. activo, inactivo)
            $table->timestamps();
        });
        Schema::create('lote_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lote_id'); // ID del lote al que pertenece
            $table->unsignedBigInteger('detalleproducto_id'); // ID del producto específico
            $table->integer('cantidad_inicial'); // Cantidad inicial del producto en el lote
            $table->integer('cantidad_disponible'); // Cantidad disponible del producto
            $table->timestamps();
            
            // Relaciones
            $table->foreign('lote_id')->references('id')->on('lotes')->onDelete('cascade');
            $table->foreign('detalleproducto_id')->references('id')->on('detalle_productos')->onDelete('cascade');
        });
        
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detalleproducto_id'); // ID del producto
            $table->integer('stock_actual'); // Cantidad actual en inventario
            $table->integer('stock_minimo'); // Stock mínimo permitido
            $table->integer('stock_maximo'); // Stock máximo permitido
            $table->integer('stock_disponible'); // Cantidad disponible para la venta
            $table->timestamps();
            
            // Relaciones
            $table->foreign('detalleproducto_id')->references('id')->on('detalle_productos')->onDelete('cascade');
        });
        
        
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detalleproducto_id');
            $table->unsignedBigInteger('lote_id')->nullable(); // Lote asociado
            $table->enum('tipo', ['recepcion', 'venta', 'ajuste'])->notNull(); // Tipo de transacción
            $table->integer('cantidad')->notNull(); // Cantidad de producto
            $table->string('descripcion')->nullable(); // Descripción de la transacción
            $table->timestamps();
        
            $table->foreign('detalleproducto_id')->references('id')->on('detalle_productos')->onDelete('cascade');
            $table->foreign('lote_id')->references('id')->on('lotes')->onDelete('cascade');
        });
        
        Schema::create('ajustes_inventario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaccion_id');
            $table->string('motivo'); // Motivo del ajuste
            $table->integer('cantidad_ajuste'); // Cantidad de ajuste
            $table->timestamps();
        
            $table->foreign('transaccion_id')->references('id')->on('transacciones')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('lotes');
       Schema::dropIfExists('lote_detalles');
       Schema::dropIfExists('transacciones');
       Schema::dropIfExists('ajustes_inventario');
    }
};
