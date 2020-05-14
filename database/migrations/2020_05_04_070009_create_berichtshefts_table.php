<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBerichtsheftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('berichtshefte', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("user_id");
            $table->integer("grade");
            $table->text("work_activities")->nullable();
            $table->text("instructions")->nullable();
            $table->text("school")->nullable();
            $table->timestamp("week")->useCurrent();
            $table->timestamps();

            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('berichtshefte');
    }
}
