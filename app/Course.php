<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\DB;
use Classes;

class Course extends Model
{

	/**
	 * Tìm kiếm các khóa học.
	 *
	 * @param  string $keyword
	 * @param  integer|  $rec_per_page
	 * @return array| $resultAfterSearch
	 */
	public $timestamps = false;
    public static function getResultSearch($keyword, $rec_per_page=5)
    {
    	$resultAfterSearch = Course::where('name','like','%'.$keyword.'%')
		                            ->orwhere('code','like','%'.$keyword.'%')
		                            ->orwhere('duration','like','%'.$keyword.'%')
		                            ->orwhere('fee','like','%'.$keyword.'%')
		                            ->orwhere('curriculum','like','%'.$keyword.'%')
		                            ->orwhere('level','like','%'.$keyword.'%')
                                    ->limit($rec_per_page)
		                            ->get();
		                            
		                             
		return $resultAfterSearch;
    }

	/**
	 * Xóa khóa học.
	 *
	 * @param  integer|  $idCourse
	 * @return void
	 */

    public static function deleteCourse($idCourse)
    {
    	$recordsRemove = Course::find($idCourse);
    	$recordsRemove->delete();
    }
    
    /**
     * Kiểm tra lớp học
     *
     * @param integer| $idCourse
     * @return $takeInfoClass
     */

    public static function checkClass($idCourse)
    {
        $takeInfoClass= DB::table('classes')->where('course_id',$idCourse)->first();
    	return $takeInfoClass;
    }

    /**
     * Tạo mới khóa học
     *
     * @param array| $infoCourse
     * @return void
     */

    public static function createCourse($infoCourse)
    {
    	$newCourse = new Course;
    	Course::insert(
            [
                'name'         =>  $infoCourse['name'],
                'code'         =>  $infoCourse['code'],
                'duration'     =>  $infoCourse['duration'],
                'fee'          =>  $infoCourse['fee'],
                'curriculum'   =>  $infoCourse['curriculum'],
                'level'        =>  $infoCourse['level']
            ]
        );
    }

    /**
     * Lấy thông tin khóa học
     *
     * @param integer| $idCourse
     * @return $infoCourse
     */

    public static function getInfoCourse($idCourse)
    {
    	$infoCourse = Course::find($idCourse);
    	return $infoCourse;
    }

     /**
     * Chỉnh sửa khóa học
     *
     * @param array| $idfoCourse
     * @param integer| $id
     * @return $editCourse
     */

	public static function editCourse($id,$infoCourse){
		$editCourse = Course::where('id',$id)->update(
                			[
                                'name'			=>	$infoCourse['name'],
                    			'code'			=>	$infoCourse['code'],
                    			'duration'		=>	$infoCourse['duration'],
                    			'fee'			=>	$infoCourse['fee'],
                    			'curriculum'	=>	$infoCourse['curriculum'],
                    			'level'		    =>	$infoCourse['level']
                            ]
                		);
	}

}
