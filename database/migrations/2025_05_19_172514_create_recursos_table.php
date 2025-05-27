<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('recursos', function (Blueprint $table) {
            $table->id();
            $table->string('hostname');
            $table->string('colaborador');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('setor_id')->constrained('setores')->onDelete('cascade');
            $table->foreignId('licenca_id')->nullable()->constrained('licencas')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('recursos');
    }
};
