<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeApplicationTplsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_tpls', function (Blueprint $table) {
            $table->unsignedBigInteger('version')->change();
            $table->foreign('version')->references('id')->on('tpl_versions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_tpls', function (Blueprint $table) {
            $table->dropForeign(['version']);
            $table->unsignedTinyInteger('number')->change();
        });
    }
}
