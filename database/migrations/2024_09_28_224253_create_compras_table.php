<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('proveedor_id');
            $table->dateTime('fecha_recepcion')->notNull();
            $table->text('notas');
            $table->decimal('costo_envio', 10, 2)
            ->default(0.00);
        $table->decimal('costo_aduana', 10, 2)
            ->default(0.00);
        $table->decimal('iva', 10, 2)
            ->default(0.00);
        $table->decimal('subtotal', 10, 2)
            ->default(0.00);
        $table->decimal('total', 10, 2)
            ->default(0.00);
            $table->integer('estado')->notNull();
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('proveedor_id')->references('id')->on('proveedors')->onDelete('restrict');
        });
        Schema::create('detalle_compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('compras_id');
            $table->unsignedBigInteger('detalleproducto_id');
            $table->integer('cantidad')->default(1)->notNull();
            $table->decimal('precio_unitario', 10, 2)->notNull();
            $table->decimal('subtotal', 10, 2)->notNull();
            $table->decimal('iva_unitario', 10, 2);
            $table->timestamps();
            $table->foreign('compras_id')->references('id')->on('compras')->onDelete('cascade');
            $table->foreign('detalleproducto_id')->references('id')->on('detalle_productos')->onDelete('restrict');
        });

        Schema::create('recepciones', function (Blueprint $table) {
            $table->id(); // Llave primaria
            $table->unsignedBigInteger('compras_id');
            $table->dateTime('fecha_recepcion')->notNull();
            $table->integer('estado')->default(1);
            $table->timestamps();
            $table->foreign('compras_id')->references('id')->on('compras')->onDelete('cascade');
        });
        Schema::create('detalle_recepciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recepciones_id');
            $table->unsignedBigInteger('detalleproducto_id');
            $table->integer('cantidad_recibida')->default(0);
            $table->timestamps();

            $table->foreign('recepciones_id')->references('id')->on('recepciones')->onDelete('cascade');
            $table->foreign('detalleproducto_id')->references('id')->on('detalle_productos');
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('compras');
        Schema::dropIfExists('detalle_compras');
       # schema::dropIfExists('costo_compras');
        Schema::dropIfExists('recepciones');
        Schema::dropIfExists('detalle_recepciones');
    }

};
