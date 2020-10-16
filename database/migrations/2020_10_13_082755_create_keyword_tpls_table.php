<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeywordTplsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keyword_tpls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tpl_id');
            $table->unsignedTinyInteger('number');
            $table->string('heading');
            $table->string('conjunction', 10)->nullable(true);
            $table->json('tpls');
            
            $table->foreign("tpl_id")->references("id")->on("application_tpls")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keyword_tpls');
    }
}
