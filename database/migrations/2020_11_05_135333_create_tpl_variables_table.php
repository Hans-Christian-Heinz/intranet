<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTplVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tpl_variables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('version')->nullable(false);
            $table->string('name')->nullable(false);
            $table->string('standard')->nullable(true);

            $table->foreign('version')->references('id')->on('tpl_versions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tpl_variables');
    }
}
