<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePassbildColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Mediumblob wird nicht von Laravel verwendet
        DB::statement("ALTER TABLE resumes MODIFY passbild MEDIUMBLOB;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE resumes MODIFY passbild BLOB;");
    }
}
