<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class StudentClass extends Model
{
    protected $table = "student_classes";
    protected $fillable = ['student_id','class_id'];

    public static function getClassOfStudent ($student_id) {
        $query = DB::table('student_classes')->select(DB::raw('classes.id, classes.name, classes.class_code, classes.price'))
        ->join('classes', 'student_classes.class_id', '=', 'classes.id')
        ->where('student_classes.student_id' ,'=', $student_id)
        ->get();
        return $query->toArray();
    }

    public static function assignClass($class_id, $student_id) 
    {
        $joined = DB::table('student_classes')->select('id')
                ->where('class_id', $class_id)
                ->where('student_id', $student_id)
                ->get()->toArray();
        if (count($joined) > 0) return false;

        DB::table('students')->where('id','=',$student_id)->update(['current_class' => $class_id]);

        $data = [
            'student_id' => $student_id,
            'class_id' => $class_id,
            'created_at' => date('Y-m-d H:i:s')
        ];

        return DB::table('student_classes')->insertGetId($data);
    }

    public static function unassignClass($class_id, $student_id) 
    {
        return DB::table('student_classes')
                ->where('class_id', $class_id)
                ->where('student_id', $student_id)
                ->delete();
    }

}
