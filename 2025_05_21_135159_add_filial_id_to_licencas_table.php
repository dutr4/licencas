public function up()
{
    Schema::table('licencas', function (Blueprint $table) {
        $table->foreignId('filial_id')->nullable()->constrained('filials')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('licencas', function (Blueprint $table) {
        $table->dropForeign(['filial_id']);
        $table->dropColumn('filial_id');
    });
}
