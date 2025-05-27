<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('notas_fiscais', function (Blueprint $table) {
            if (!Schema::hasColumn('notas_fiscais', 'filial_id')) {
                $table->unsignedBigInteger('filial_id')->after('empresa_id');
                $table->foreign('filial_id')->references('id')->on('filiais')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notas_fiscais', function (Blueprint $table) {
            //
        });
    }
};
