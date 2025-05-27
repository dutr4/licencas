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
	Schema::create('recursos', function (Blueprint $table) {
	    $table->id();
	    $table->string('hostname');
	    $table->string('colaborador');
	    $table->foreignId('empresa_id')->constrained()->onDelete('cascade');
	    $table->foreignId('setor_id')->constrained('setores')->onDelete('cascade');
	    $table->foreignId('licenca_id')->nullable()->constrained()->onDelete('set null');
	    $table->timestamps();
	});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recursos');
    }
};
