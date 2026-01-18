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
        Schema::create('caixas', function (Blueprint $table) {
            $table->id();
            $table->timestamp('data_abertura')->nullable();
            $table->timestamp('data_fechamento')->nullable();
            $table->decimal('saldo_inicial', 12, 2)->default(0);
            $table->decimal('saldo', 12,2)->default(0);
            $table->decimal('saldo_final', 12, 2)->nullable();
            $table->integer('total_vendas')->default(0);
            $table->enum('status', ['aberto', 'fechado'])->default('fechado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caixas');
    }
};
