<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Student extends Model
{
	protected $table = 'students';
    protected $fillable = ['name','email','address','mobile','birthday','birthyear','parent_id','gender','created_at','updated_at'];
    
    /**
     * Hiển thị danh sách các bản ghi.
     *
     * @param  string $keyword
     * @param  numeric $record
     * @param  numeric $page
     * @return $search
     */
	public static function search($keyword, $record,$page = 1){
        $start = ($page - 1) * $record;
        /* $search = Student::orderBy('id','desc')->where('name','like','%'.$keyword.'%')
                                ->orwhere('email','like','%'.$keyword.'%')
	                            ->orwhere('student_code','like','%'.$keyword.'%')
	                            ->orwhere('address','like','%'.$keyword.'%')
	                            ->orwhere('mobile','like','%'.$keyword.'%')
                                ->get(); */

        
        $search = DB::table('students')
                    ->select('students.*', 'parents.fullname as parent_name', 'classes.name as class_name')
                    ->leftJoin('parents', 'parents.id', '=', 'students.parent_id')
                    ->leftJoin('classes', 'classes.id', '=', 'students.current_class')
                    ->where('students.name','like','%'.$keyword.'%')
                    ->orwhere('students.email','like','%'.$keyword.'%')
                    ->orwhere('students.student_code','like','%'.$keyword.'%')
                    ->orwhere('students.address','like','%'.$keyword.'%')
                    ->orwhere('students.mobile','like','%'.$keyword.'%')
                    ->orwhere('parents.fullname','like','%'.$keyword.'%')
                    ->orwhere('classes.name','like','%'.$keyword.'%')
                    ->orderBy('students.id','desc')->get();

		return $search;
	}

	/**
     * Xóa một bản ghi dựa vào id.
     *
     * @param  int  $id
     * @return void
     */
	public static function deleteStudent($id){
		return DB::table('students')->where('id',$id)->delete();
	}

	/**
     * Thêm mới một bản ghi.
     *
     * @param  array $data
     * @return void
     */
	public static function store1($data){
		$student = DB::table('students')->insert([
    		['name' => $data['name'],
                'email' => $data['email'],
    			'student_code' => $data['student_code'],
    			'address' => $data['address'],
    			'mobile' => $data['mobile'],
    			'birthday' => $data['birthday'],
    			'gender' => $data['gender'],
    			'created_at' => $data['created_at'],
    			'updated_at' => $data['updated_at'],
    		],
		]);
		return $student;
    }

    public static function getStudentByCrmID ($crm_id) 
    {
        return DB::table('students')->select('*')->where('crm_id',$crm_id)->get()->toArray();
    }
    
        /**
     * Thêm mới một bản ghi.
     *
     * @param  array $data
     * @return void
     */
    public static function insert($data)
    {
        $id = DB::table('students')->insertGetId($data);
        return $id;
    }

    /**
     * Hiển thị danh sách các bản ghi.
     *
     * @param  int $id
     * @return $student
     */
    public static function getStudent($id)
    {
        $student = Student::find($id);
        return $student;
    }

    /**
     * Update thông tin học sinh.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  array  $data
     * @return void
     */
    public static function update1($data, $id){
        $student = DB::table('students')->where('id', $id)
                    ->update([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'student_code' => $data['student_code'],
                        'address' => $data['address'],
                        'mobile' => $data['mobile'],
                        'birthday' => $data['birthday'],
                        'gender' => $data['gender'],
                        'created_at' => $data['created_at'],
                        'updated_at' => $data['updated_at'],
                    ]);
        return $student;
    }

}