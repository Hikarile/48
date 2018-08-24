<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
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
            $table->text('album_id');
            $table->text('image_id');
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('width');
            $table->integer('height');
            $table->integer('size');
            $table->integer('views')->default(0);
            $table->text('file');
            $table->text('delete_token')->nullable();
            $table->timestamps();
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
