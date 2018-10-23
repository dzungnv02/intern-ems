<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRevNExp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rev_n_exp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('type');
            $table->tinyInteger('reason');
            $table->bigInteger('student_id');
            $table->integer('class_id');
            $table->date('start_date');	
            $table->date('end_date');	
            $table->integer('duration');
            $table->text('payer');
            $table->float('amount', 8, 2);
            $table->string('currency',5)->default('VND');
            $table->string('created_by',45);
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
        Schema::table('rev_n_exp', function (Blueprint $table) {
            //
        });
    }
}
