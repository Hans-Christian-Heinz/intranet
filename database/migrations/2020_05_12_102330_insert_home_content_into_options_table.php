<?php

use App\Option;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertHomeContentIntoOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Option::where("key", "home_content")->delete();
        Option::addOption("home_content", "<h1>Home</h1><p>Lorem ipsum dolor sit amet.</p>");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Option::where("key", "home_content")->delete();
    }
}
