<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyStudentTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_tracking', function (Blueprint $table) {
            $table->renameColumn('left_date', 'start_time');
            $table->renameColumn('joined_date', 'end_time');
        });

        Schema::table('student_tracking', function (Blueprint $table) {
            $table->integer('from_class')->nullable()->change();
            $table->integer('to_class')->nullable()->change();
            $table->dateTime('start_time')->nullable()->change();
            $table->dateTime('end_time')->nullable()->change();
            $table->string('note', 255)->nullable()->after('end_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_tracking', function (Blueprint $table) {
            //
        });
    }
}
