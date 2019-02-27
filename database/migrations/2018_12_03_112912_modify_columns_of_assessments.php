<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyColumnsOfAssessments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dateTime('assignment_date')->nullable()->change();
            $table->integer('teacher_id')->nullable()->change();
            $table->renameColumn('assignment_date', 'assessment_date');
            $table->renameColumn('asignment_result', 'assessment_result');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
