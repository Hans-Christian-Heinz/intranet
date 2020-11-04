<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('contact')->nullable(true);
            $table->string('function')->nullable(true);
            $table->string('email')->nullable(true);
            $table->string('phone')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn("contact");
            $table->dropColumn("function");
            $table->dropColumn("email");
            $table->dropColumn("phone");
        });
    }
}
