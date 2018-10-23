<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNineRecordsToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            DB::table('students')->insert(
                array(
                    array(
                        'name' => 'Lê Văn B',
                        'email' => 'va21nb@gmail.com',
                        'address' => 'Hà Nam',
                        'mobile' => '0252354126',
                        'birthday' => '1992-10-22',
                        'gender' => 0,
                        'updated_at' => '2018-08-24 13:02:30',
                        'created_at' => '2018-08-24 13:02:33',
                    ),
                    array(
                        'name' => 'Nguyễn Văn A',
                        'email' => 'ah21hah@gmail.com',
                        'address' => 'Quảng Ninh',
                        'mobile' => '0155622165',
                        'birthday' => '2000-07-12',
                        'gender' => 0,
                        'updated_at' => '2018-08-24 13:02:30',
                        'created_at' => '2018-08-24 13:02:33',
                    ),
                    array(
                        'name' => 'Trần Anh C',
                        'email' => 'trananhh@gmail.com',
                        'address' => 'Lào Cai',
                        'mobile' => '0156232165',
                        'birthday' => '2001-08-12',
                        'gender' => 1,
                        'updated_at' => '2018-08-24 13:02:30',
                        'created_at' => '2018-08-24 13:02:33',
                    ),
                    array(
                        'name' => 'Lý Quang C',
                        'email' => 'ah111hah@gmail.com',
                        'address' => 'Vĩnh Phúc',
                        'mobile' => '0156232165',
                        'birthday' => '2001-08-12',
                        'gender' => 1,
                        'updated_at' => '2018-08-24 13:02:30',
                        'created_at' => '2018-08-24 13:02:33',
                    ),
                    array(
                        'name' => 'Trần Văn D',
                        'email' => 'rewr@gmail.com',
                        'address' => 'Nam Định',
                        'mobile' => '0164332165',
                        'birthday' => '2002-07-30',
                        'gender' => 1,
                        'updated_at' => '2018-08-24 13:02:30',
                        'created_at' => '2018-08-24 13:02:33',
                    ),
                    array(
                        'name' => 'Lại Văn Sâm',
                        'email' => 'ahahahah@gmail.com',
                        'address' => 'Vĩnh Phúc',
                        'mobile' => '0432232165',
                        'birthday' => '1998-02-22',
                        'gender' => 0,
                        'updated_at' => '2018-08-24 13:02:30',
                        'created_at' => '2018-08-24 13:02:33',
                    ),
                    array(
                        'name' => 'Lê Vân Anh',
                        'email' => 'ahahahah@gmail.com',
                        'address' => 'Nam Định',
                        'mobile' => '0155232165',
                        'birthday' => '2002-09-10',
                        'gender' => 1,
                        'updated_at' => '2018-08-24 13:02:30',
                        'created_at' => '2018-08-24 13:02:33',
                    ),
                    array(
                        'name' => 'Lê Ngọc Anh',
                        'email' => 'dsds@gmail.com',
                        'address' => 'Sài Gòn',
                        'mobile' => '0156232231',
                        'birthday' => '1994-01-11',
                        'gender' => 1,
                        'updated_at' => '2018-08-24 13:02:30',
                        'created_at' => '2018-08-24 13:02:33',
                    ),
                    array(
                        'name' => 'Trần Văn E',
                        'email' => 'jjjjh@gmail.com',
                        'address' => 'Vĩnh Phúc',
                        'mobile' => '0156322165',
                        'birthday' => '2002-08-11',
                        'gender' => 0,
                        'updated_at' => '2018-08-24 13:02:30',
                        'created_at' => '2018-08-24 13:02:33',
                    ),
                    array(
                        'name' => 'Phạm Văn B',
                        'email' => 'vanb@gmail.com',
                        'address' => 'Hà Nam',
                        'mobile' => '0256354126',
                        'birthday' => '1995-11-22',
                        'gender' => 1,
                        'updated_at' => '2018-08-24 11:02:30',
                        'created_at' => '2018-08-24 11:02:33',
                    ),
                    array(
                        'name' => 'Trần Văn C',
                        'email' => 'ahahahah@gmail.com',
                        'address' => 'Vĩnh Phúc',
                        'mobile' => '0156232165',
                        'birthday' => '2001-08-12',
                        'gender' => 1,
                        'updated_at' => '2018-08-24 11:02:30',
                        'created_at' => '2018-08-24 11:02:33',
                    ),
                )
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            //
        });
    }
}
