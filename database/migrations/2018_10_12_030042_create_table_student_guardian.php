<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStudentGuardian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_guardian', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fullname', 100);
            $table->string('phone_1', 20);
            $table->string('phone_2', 20);
            $table->string('email', 65);
            $table->text('students');
            $table->string('address', 100);
            $table->tinyInteger('source');
            $table->string('created_by', 45);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *4
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_guardian');
    }
}
