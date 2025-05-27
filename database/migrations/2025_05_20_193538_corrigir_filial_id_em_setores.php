<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('setores', function (Blueprint $table) {
            if (!Schema::hasColumn('setores', 'filial_id')) {
	    $table->foreignId('filial_id')->nullable()->constrained('filiais')->after('empresa_id');
            }
        });
    }

    public function down(): void {
        Schema::table('setores', function (Blueprint $table) {
            if (Schema::hasColumn('setores', 'filial_id')) {
                $table->dropForeign(['filial_id']);
                $table->dropColumn('filial_id');
            }
        });
    }
};
