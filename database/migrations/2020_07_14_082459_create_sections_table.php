<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proposal_id')->nullable();
            $table->unsignedBigInteger('documentation_id')->nullable();
            $table->string('name');
            $table->string('heading');
            $table->unsignedSmallInteger('sequence');
            $table->boolean("is_locked")->default(false);
            $table->timestamps();

            $table->foreign("proposal_id")->references("id")->on("proposals")->onDelete("cascade");
            $table->foreign("documentation_id")->references("id")->on("documentations")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sections');
    }
}
