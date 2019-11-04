<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->unsigned()->default(0)->comment('0:tutorfee; 1,2,3');
            $table->string('message', 255);
            $table->string('url', 255);
            $table->integer('sender');
            $table->text('receiver')->nullable()->comment('staff json object: {staff_id:number, status:0|1}');
            $table->timestamp('sent_time');
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
        Schema::dropIfExists('notifications');
    }
}
