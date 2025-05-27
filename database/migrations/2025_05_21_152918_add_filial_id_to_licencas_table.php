<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilialIdToLicencasTable extends Migration
{

    public function down()
    {
        Schema::table('licencas', function (Blueprint $table) {
            $table->dropForeign(['filial_id']);
            $table->dropColumn('filial_id');
        });
    }
}
