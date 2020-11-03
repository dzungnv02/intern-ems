<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSendMailDateToRevNExpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rev_n_exp', function (Blueprint $table) {
            $table->dateTime('send_mail_date')->nullable()->after('invoice_status');
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
