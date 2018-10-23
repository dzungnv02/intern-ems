<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
    public $timestamps = false;

    public $table = 'exams';
    /**
     * Danh sách các kì thi.
     *
     * @param  array  $keyword ,$record, $page
     * @return search
     */
    public static function search($keyword,$record,$page = 1,$classid){
        if ($classid != 0){
            $search = Examination::join('classes','classes.id','=','exams.class_id')
            ->select('exams.*','classes.name as name_class')
            ->where('exams.class_id','=',$classid)
            ->get();
            return $search;

        }else{
            $start = ($page - 1) * $record;
            $search= Examination::join('classes','classes.id','=','exams.class_id')
                        ->select('exams.*','classes.name as name_class')
                         ->where('exams.name', 'like', '%' .$keyword. '%')
                         ->orWhere('exams.note', 'like', '%' .$keyword. '%')
                         ->orWhere('exams.duration', 'like', '%' .$keyword. '%')
                         ->orWhere('exams.start_day', 'like', '%' .$keyword. '%')
                         ->orWhere('classes.name', 'like', '%' .$keyword. '%')
                         //->offset($start)
                         ->get();
    
                        return $search;
        }
        
    }
    /**
     * Thêm  kì thi.
     *
     * @param array  $createEx
     * @return createExam
     */
        public static function createExam($createEx){
        $createExam = Examination::insert(['name'=> $createEx['name'],
                                        'start_day'=>$createEx['start_day'],
                                        'duration'=> $createEx['duration'],
                                        'note'=>$createEx['note'],
                                        'class_id'=>$createEx['class_id'],
                                        'created_at'=>  date('Y-m-d H:i:s'),
                                        'updated_at' =>  date('Y-m-d H:i:s')
                                        ]);
        return $createExam;
    }
    /**
     * xóa kì thi.
     *
     * @param  string  $id
     * @return void
     */
    public static function deleteExam($id){
        $deleteExam = Examination::find($id);
        $deleteExam->delete();
        return $deleteExam;
    }
    /**
     * update kì thi.
     *
     * @param  array  $data,$id
     * @return void
     */
    public static function updateExam($data,$id){
        $updateExam = Examination::where('id',$id)
        ->update([
            'name' => $data['name'],
            'start_day' => $data['start_day'],
            'duration' => $data['duration'],
            'note' => $data['note'],
            'class_id' => $data['class_id'],
            'updated_at' =>  date('Y-m-d H:i:s')
        ]);
        return $updateExam;
    }
    /**
     * edit kì thi.
     *
     * @param  string  $id
     * @return editExam
     */

    public static function editExam($id){
        $editExam = Examination::join('classes','classes.id','=','exams.class_id')
        ->select('exams.*','classes.name as name_class')
        ->find($id);
        return $editExam;
    }
    /**
     * Danh sách các lớp.
     *
     * @return getName
     */
    public static function getNameClass(){
        $getName = DB::table('classes')->get();
        return $getName;
    }
}

