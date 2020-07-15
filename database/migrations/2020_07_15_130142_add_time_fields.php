<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentations', function (Blueprint $table) {
            $table->unsignedSmallInteger('planung')->default(0);
            $table->unsignedSmallInteger('entwurf')->default(0);
            $table->unsignedSmallInteger('implementierung')->default(0);
            $table->unsignedSmallInteger('test')->default(0);
            $table->unsignedSmallInteger('abnahme')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documentations', function (Blueprint $table) {
            $table->dropColumn('planung');
            $table->dropColumn('entwurf');
            $table->dropColumn('implementierung');
            $table->dropColumn('test');
            $table->dropColumn('abnahme');
        });
    }
}
