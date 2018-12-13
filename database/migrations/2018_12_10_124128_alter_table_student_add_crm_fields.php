<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableStudentAddCrmFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->date('tutor_fee_expired_date')->nullable()->after('source');
            $table->date('branch_transfer_date')->nullable()->after('tutor_fee_expired_date');
            $table->date('class_joining_date')->nullable()->after('branch_transfer_date');
            $table->date('withdrawal_date')->nullable()->after('branch_transfer_date');
            $table->text('crm_assessment')->nullable()->after('parent_crm_id');
            $table->string('current_school')->nullable()->after('withdrawal_date');
            $table->string('deposit_amount')->nullable()->after('current_school');
            $table->string('current_class')->nullable()->after('deposit_amount');
            $table->date('deposit_date')->nullable()->after('withdrawal_date');
            $table->text('crm_contact')->nullable()->after('crm_assessment');
            $table->dateTime('register_date')->nullable()->after('branch_transfer_date');
            $table->text('crm_dependent_staff')->nullable()->after('crm_assessment');
            $table->text('crm_register_branch')->nullable()->after('crm_dependent_staff');
            $table->string('first_register_branch')->nullable()->after('crm_register_branch');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->renameColumn('parent_crm_id', 'crm_parent_id');
            $table->renameColumn('cmr_parent', 'crm_parent');
            $table->renameColumn('cmr_owner', 'crm_owner');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            //
        });
    }
}
