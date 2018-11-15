<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTeacherSchedulesAddClassId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_schedules', function (Blueprint $table) {
            $table->integer('class_id')->nullable()->after('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_schedules', function (Blueprint $table) {
            $table->dropColumn('class_id');
        });
    }
}
