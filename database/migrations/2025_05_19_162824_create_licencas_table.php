<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('licencas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // Ex: 000001
	    $table->foreignId('nota_fiscal_item_id')->constrained('nota_fiscal_itens')->onDelete('cascade');
            $table->foreignId('empresa_id')->constrained()->onDelete('cascade');
	    $table->foreignId('setor_id')->constrained('setores')->onDelete('cascade');
            $table->string('chave')->nullable(); // chave/serial
            $table->enum('status', ['disponivel', 'vinculada'])->default('disponivel');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('licencas');
    }
};
