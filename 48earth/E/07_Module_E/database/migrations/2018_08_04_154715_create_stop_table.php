<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stops', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('train_id')->unsigned()->comment('列車關聯');
            $table->integer('station_id')->unsigned()->comment('車站關聯');
            $table->integer('time')->comment('行駛時間');
            $table->integer('stop_time')->comment('停站時間');
            $table->integer('money')->comment('價錢');
            $table->timestamps();

            $table->foreign('train_id')
                ->references('id')
                ->on('trains')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stops');
    }
}
