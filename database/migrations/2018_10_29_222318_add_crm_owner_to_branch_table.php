<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCrmOwnerToBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch', function (Blueprint $table) {
            $table->string('crm_owner_id', 20)->nullable()->after('crm_id'); 
            $table->string('crm_owner_name', 255)->nullable()->after('crm_owner_id'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch', function (Blueprint $table) {
            $table->dropColumn('crm_owner_id'); 
            $table->dropColumn('crm_owner_name'); 
        });
    }
}
