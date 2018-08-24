<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trains', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id');
            $table->string('train_name');
            $table->integer('car_count');
            $table->integer('car_people');
            $table->integer('car_all');
            $table->integer('mon');
            $table->integer('tue');
            $table->integer('wed');
            $table->integer('thu');
            $table->integer('fri');
            $table->integer('sat');
            $table->integer('sun');
            $table->string('station_s');
            $table->string('station_s_time');
            $table->string('station_e');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('trains');
    }
}
