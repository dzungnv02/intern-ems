<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class StudentClass extends Model
{
    protected $table = "student_classes";
    protected $fillable = ['student_id','class_id'];

    public static function getClassOfStudent ($student_id) {
        $query = DB::table('student_classes')->select(DB::raw('classes.id, classes.name, classes.class_code'))
        ->join('classes', 'student_classes.class_id', '=', 'classes.id')
        ->where('student_classes.student_id' ,'=', $student_id)
        ->get();
        return $query->toArray();
    }
}
