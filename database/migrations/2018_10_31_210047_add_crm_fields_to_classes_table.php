<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCrmFieldsToClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->string('crm_id', 20)->nullable()->after('class_size');
            $table->string('crm_course', 255)->nullable()->after('crm_id');
            $table->text('crm_teacher')->nullable()->after('crm_course');
            $table->text('crm_schedule')->nullable()->after('crm_teacher');
            $table->text('crm_branch')->nullable()->after('crm_schedule');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('crm_id');
            $table->dropColumn('crm_course');
            $table->dropColumn('crm_teacher');
            $table->dropColumn('crm_schedule');
            $table->dropColumn('crm_branch');
        });
    }
}
