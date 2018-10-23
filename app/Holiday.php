<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Holiday extends Model
{
    protected $table = 'holidays';
    protected $fillable = ['holidays','created_at','updated_at'];

    /**
     * Hiển thị danh sách các bản ghi.
     *
     * @return $holiday
     */
	public static function getListHoliday(){
		$holiday = DB::table('holidays')->get();
		return $holiday;
	}

	/**
     * Xóa một bản ghi dựa vào id.
     *
     * @param  int  $id
     * @return void
     */
	public static function deleteHoliday($id){
		return DB::table('holidays')->where('id',$id)->delete();
	}

	/**
     * Thêm mới một bản ghi.
     *
     * @param  array $data
     * @return void
     */
	public static function addHoliday($data){
		$student = DB::table('holidays')->insert([
		    		'holiday' => $data['holiday'],
                    'created_at' => $data['created_at'],
                    'updated_at' => $data['updated_at'],
				]);
	}

}
