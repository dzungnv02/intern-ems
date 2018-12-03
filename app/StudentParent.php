<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentParent extends Model
{
    protected $table = 'student_parents';

    public static function getParentsOfStudent ($student_id)
    {
        $list = DB::table('student_parents')
                ->select('parents.*')
                ->join('parents', 'parents.id', '=', 'student_parents.parent_id')
                ->where('student_id', $student_id)
                ->get();
        return $list;
    }

    public static function insert($data) 
    {
        if (count($data)) {
            $obj = new StudentParent;
            foreach($data as $field => $value) {
                $obj->$field = $value;
            }
            return $obj->save();
        }

        return false;
    }

    public static function deleteByStudent ($student_id) 
    {
        return StudentParent::where('student_id', $student_id)->delete();
    }
}
