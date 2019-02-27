<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableParentsAddSomeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->string('other_phone', 15)->nullable()->after('phone');
            $table->string('working_phone', 15)->nullable()->after('other_phone');
            $table->string('working_place', 255)->nullable()->after('working_phone');
            $table->integer('crm_register_branch')->nullable()->after('working_place');
            $table->dateTime ('crm_created_at')->nullable()->after('crm_register_branch');
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
