<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // quem realizou a ação
            $table->string('acao'); // exemplo: created, updated, deleted
            $table->string('modulo'); // exemplo: Empresa, Setor, Licença
            $table->text('detalhes')->nullable(); // descrição da ação
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
