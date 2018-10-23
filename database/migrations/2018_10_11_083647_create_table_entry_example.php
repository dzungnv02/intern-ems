<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEntryExample extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /* 
        php artisan make:migration create_table_entry_example --create=entry_example
        */
        Schema::create('entry_example', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('student_id');
            $table->integer('teacher_id');
            $table->integer('branch_id');
            $table->datetime('example_date');
            $table->datetime('trial_date');
            $table->string('mark', 10);
            $table->string('result', 255);
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
        Schema::dropIfExists('entry_example');
    }
}
