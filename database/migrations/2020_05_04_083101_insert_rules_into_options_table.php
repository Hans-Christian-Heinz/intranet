<?php

use App\Option;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertRulesIntoOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Option::addOption("rules", "<h3>Regeln</h3><p>Lorem ipsum dolor sit amet.</p>");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Option::where("key", "regeln")->delete();
    }
}
