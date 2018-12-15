<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyRollCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roll_calls', function (Blueprint $table) {
            $table->dropColumn('time_call');
            $table->bigInteger('student_id')->after('status');
            $table->bigInteger('timetable_id')->after('student_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roll_calls', function (Blueprint $table) {
            //
        });
    }
}
