<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('filiais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->onDelete('cascade');
            $table->string('nome');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('filiais');
    }
};
