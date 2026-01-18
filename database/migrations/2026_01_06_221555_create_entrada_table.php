<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entrada', function (Blueprint $table) {
            $table->id();
            $table->json('itens'); // ← já nasce com o JSON
            $table->text('observacao')->nullable();
            $table->decimal('valor_total', 10, 2);
            $table->timestamps();
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('entrada');
    }
};
