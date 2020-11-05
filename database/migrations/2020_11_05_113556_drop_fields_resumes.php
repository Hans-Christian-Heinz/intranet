<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropFieldsResumes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resumes', function (Blueprint $table) {
            $table->dropColumn('pb_datatype');
            $table->dropColumn('sig_datatype');
            $table->dropColumn('passbild');
            $table->dropColumn('signature');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resumes', function (Blueprint $table) {
            $table->enum('pb_datatype', ['png', 'jpg', 'jpeg'])->default('png');
            $table->enum('sig_datatype', ['png', 'jpg', 'jpeg'])->default('png');
            $table->binary('signature')->nullable(true);
            $table->binary('passbild')->nullable(true);
        });
    }
}
