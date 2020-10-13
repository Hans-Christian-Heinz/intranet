<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationTplsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_tpls', function (Blueprint $table) {
            $table->id();
            $table->boolean('fix')->default(false);
            $table->boolean('is_heading')->default(false);
            $table->boolean('choose_keywords')->default(false);
            $table->unsignedTinyInteger('number');
            $table->string('name');
            $table->string('heading')->nullable(true);
            $table->json('tpls');
            $table->unsignedSmallInteger('version');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_tpls');
    }
}
