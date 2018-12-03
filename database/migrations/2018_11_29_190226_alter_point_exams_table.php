<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPointExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('point_exams', function (Blueprint $table) {
            $table->dropColumn('student_id');
        });

        Schema::table('point_exams', function (Blueprint $table) {
            $table->bigInteger('student_id')->after('examination_id');
            $table->text('note')->after('student_id')->nullable();
            $table->integer('teacher_id')->nullable()->after('note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('point_exams', function (Blueprint $table) {
            //
        });
    }
}
