<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStudentTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_tracking', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('student_id');
            $table->tinyInteger('act_type')->comment('0: new contact; 1: kiểm tra đầu vào; 2: nhập học; 3:chuyển lớp; 4: chuyển trung tâm; 5: lên lớp; 6: kết thúc');
            $table->integer('from_class');
            $table->integer('to_class');
            $table->date('left_date');
            $table->date('joined_date');
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
        Schema::dropIfExists('student_tracking');
    }
}
