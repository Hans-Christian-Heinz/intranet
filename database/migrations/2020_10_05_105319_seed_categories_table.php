<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        $categories = [
            ["name" => "Kommunikation", "created_at" => $now, "updated_at" => $now],
            ["name" => "Kollegenzusammenhalt", "created_at" => $now, "updated_at" => $now],
            ["name" => "Arbeitsatmosphäre", "created_at" => $now, "updated_at" => $now],
            ["name" => "Interessante Aufgaben", "created_at" => $now, "updated_at" => $now],
            ["name" => "Arbeitsbedingungen", "created_at" => $now, "updated_at" => $now],
            ["name" => "Behindertengerecht", "created_at" => $now, "updated_at" => $now],
        ];

        DB::table('categories')->insert($categories);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
