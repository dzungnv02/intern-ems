<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiscountTypeToRevNExpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rev_n_exp', function (Blueprint $table) {
            $table->string('discount_type',5)->default('p')->after('discount_desc');
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
