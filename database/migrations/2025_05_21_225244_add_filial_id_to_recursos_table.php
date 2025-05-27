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
        Schema::table('recursos', function (Blueprint $table) {
            $table->unsignedBigInteger('filial_id')->nullable()->after('empresa_id');
            $table->foreign('filial_id')->references('id')->on('filiais')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('recursos', function (Blueprint $table) {
            $table->dropForeign(['filial_id']);
            $table->dropColumn('filial_id');
        });
    }
};
