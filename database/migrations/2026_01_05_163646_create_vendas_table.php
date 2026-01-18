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
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caixa_id')->constrained('caixas')->cascadeOnDelete();
            $table->integer('numero_nota');
            $table->string('cliente_nome')->nullable();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('desconto', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->enum('forma_pagamento', [
                'dinheiro',
                'debito',
                'credito',
                'pix'
            ])->default('pendente');
            $table->timestamps();
            $table->unique(['caixa_id', 'numero_nota']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendas');
    }
};
