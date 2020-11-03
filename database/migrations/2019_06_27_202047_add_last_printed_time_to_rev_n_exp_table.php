<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastPrintedTimeToRevNExpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rev_n_exp', function (Blueprint $table) {
            $table->timestamp('last_printed_time')->nullable()->after('invoice_content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rev_n_exp', function (Blueprint $table) {
            //
        });
    }
}
