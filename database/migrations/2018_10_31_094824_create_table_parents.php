<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableParents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fullname', 100);
            $table->string('email', 255)->nullable();
            $table->string('phone', 60)->nullable();
            $table->string('parent_role', 100)->nullable();
            $table->string('facebook', 255)->nullable();
            $table->integer('branch_id')->nullable();
            $table->string('crm_id', 20)->nullable();
            $table->text('crm_branch')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parents');
    }
}
