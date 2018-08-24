<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('album_id');
			$table->string('title');
			$table->string('description');
			$table->string('image');
			$table->integer('width');
			$table->integer('height');
			$table->integer('size');
            $table->integer('views');
            $table->integer('cover');
            $table->string('link');
			$table->string('tokne');
            $table->integer('created');
            $table->string('delete');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
