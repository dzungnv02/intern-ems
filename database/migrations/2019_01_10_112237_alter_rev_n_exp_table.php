<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRevNExpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rev_n_exp', function (Blueprint $table) {
            $table->string('invoice_number', 10)->unique()->after('id');
            $table->unsignedInteger('discount')->nullable()->after('amount');
            $table->string('discount_desc')->nullable()->after('discount');
            $table->tinyInteger('invoice_status')->default(0)->after('discount_desc')->comment('0:temporary saved; 1:saved and printed;2: re-printed');

            $table->smallInteger('type')->default(1)->change();
            $table->smallInteger('reason')->nullable()->change();
            $table->bigInteger('student_id')->nullable()->change();
            $table->integer('class_id')->nullable()->change();
            $table->date('start_date')->nullable()->change();	
            $table->date('end_date')->nullable()->change();	
            $table->integer('duration')->nullable()->change();
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
