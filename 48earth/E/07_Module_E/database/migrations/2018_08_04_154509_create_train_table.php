<?php

use Illuminate\Support\Facades\Schema;
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
            $table->integer('type_id')->unsigned()->comment('車種關聯');
            $table->integer('code')->comment('列車編號');
            $table->string('day')->comment('行車星期');
            $table->string('start_time')->comment('出發時間');
            $table->integer('people')->comment('單一車廂載客數');
            $table->integer('car')->comment('車廂數量');
            $table->timestamps();

            $table->foreign('type_id')
                ->references('id')
                ->on('types')
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
        Schema::dropIfExists('trains');
    }
}
