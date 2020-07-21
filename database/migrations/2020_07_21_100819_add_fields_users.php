<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('fachrichtung', ['Anwendungsentwicklung', 'Systemintegration', 'Ausbilder'])
                ->default('Anwendungsentwicklung');
            $table->string('strasse')->nullable();
            $table->string('ort')->nullable();
            $table->unsignedSmallInteger('plz')->nullable();
            $table->string('hausnr')->nullable();

            $table->dropColumn('is_admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean("is_admin")->default(false);

            $table->dropColumn('fachrichtung');
            $table->dropColumn('strasse');
            $table->dropColumn('ort');
            $table->dropColumn('plz');
            $table->dropColumn('hausnr');
        });
    }
}
