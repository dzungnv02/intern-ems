<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrialClassIdToEntryAsignment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entry_asignment', function (Blueprint $table) {
            $table->integer('trial_class_id')->nullable()->after('trial_start_date');
        });

        Schema::rename('entry_asignment', 'assessments');
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
