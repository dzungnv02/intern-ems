<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentActivity extends Model
{
    protected $table = 'student_tracking';

    public static function getActivityOfStudent($student_id)
    {
        $list = DB::table('student_tracking')
                ->select('*')
                ->where('student_id', $student_id)
                ->get();
        return $list;
    }

    public static function createActivity($data)
    {
        if (count($data)) {
            $obj = new StudentActivity;
            foreach($data as $field => $value) {
                $obj->$field = $value;
            }
            return $obj->save();
        }
        return false;
    }

    public static function updateActivity($id, $data)
    {
        if (count($data) && $id) {
            $obj = StudentActivity::find($id);
            if ($obj) {
                foreach($data as $field => $value) {
                    $obj->$field = $value;
                }
                return $obj->save();
            }
            return false;
        }
        return false;
    }

    public static function deleteActivity($id) 
    {
        return StudentActivity::where('id', $id)->delete();
    }

    public static function deleteByStudent($student_id)
    {
        return StudentActivity::where('student_id', $student_id)->delete();
    }
}
