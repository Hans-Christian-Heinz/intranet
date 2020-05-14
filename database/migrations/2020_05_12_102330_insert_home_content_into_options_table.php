<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertHomeContentIntoOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('options')->insert([
            'key' => 'home_content',
            'value' => file_get_contents('resources/home.html'),
            'created_at' => Carbon::now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('options')->where('key', 'home_content')->delete();
    }
}
