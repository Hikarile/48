<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('train_id')->unsigned()->comment('列車關聯');
            $table->integer('train_name')->comment('車站編號');
            $table->string('code')->comment('調票編號');
            $table->string('phone')->comment('手機');
            $table->string('day')->comment('發車日期');
            $table->string('time')->comment('發車時間');
            $table->integer('from')->comment('從');
            $table->integer('to')->comment('到');
            $table->integer('count')->comment('張數');
            $table->integer('money')->comment('價錢');
            $table->softDeletes();
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
        Schema::dropIfExists('books');
    }
}
