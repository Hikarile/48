<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('train_id');
            $table->string('booking_id');
            $table->string('cellphone');
            $table->string('booking_day');
            $table->string('start_day');
            $table->string('time');
            $table->string('station_s');
            $table->string('station_e');
            $table->string('day');
            $table->integer('count');
            $table->integer('money_one');
            $table->integer('money_all');
            $table->integer('del');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bookings');
    }
}
