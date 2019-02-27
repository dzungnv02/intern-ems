<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatEntryAssignmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry_asignment', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('student_id');
            $table->integer('teacher_id');
            $table->timestamp('assignment_date');
            $table->string('asignment_result', 255)->nullable();
            $table->timestamp('trial_start_date')->nullable();
            $table->integer('staff_id');
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
        Schema::dropIfExists('entry_asignment');
    }
}
