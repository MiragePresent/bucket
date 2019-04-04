<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("files", function (Blueprint $table) {
            $table->increments("id");
            $table->char("hash", 10)->unique();
            $table->string("name");
            $table->string("resource", 300)->unique();
            $table->string("path")->unique();
            $table->integer("size")->nullable();
            $table->tinyInteger("status");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
