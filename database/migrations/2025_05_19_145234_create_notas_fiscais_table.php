<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notas_fiscais', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->foreignId('empresa_id')->constrained()->onDelete('cascade');
            $table->date('data_emissao');
            $table->string('arquivo')->nullable(); // Caminho do PDF
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('notas_fiscais');
    }
};
