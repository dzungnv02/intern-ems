<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableParentsAddCrmFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->text('cmr_owner')->nullable()->after('crm_branch');
            $table->text('cmr_contact')->nullable()->after('crm_branch');
            $table->text('cmr_deal')->nullable()->after('crm_branch');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parents', function (Blueprint $table) {
            //
        });
    }
}
