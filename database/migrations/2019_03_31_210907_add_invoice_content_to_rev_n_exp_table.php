<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceContentToRevNExpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rev_n_exp', function (Blueprint $table) {
            $table->text('invoice_content')->nullable()->after('send_mail_date');
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
