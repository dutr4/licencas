<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('nota_fiscal_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_fiscal_id')->constrained('notas_fiscais')->onDelete('cascade');
            $table->string('descricao');
            $table->integer('quantidade')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('nota_fiscal_itens');
    }
};
