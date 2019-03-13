<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Assessment extends Model
{
    protected $table = 'assessments';

    public static function getAssesmentOfStudent ($student_id) 
    {
        $assessment = DB::table('assessments')
            ->select('assessments.*', 'classes.name as class_name')
            ->leftJoin('classes', 'classes.id', '=', 'assessments.trial_class_id')
            ->where('assessments.student_id', $student_id)->first();
        return $assessment;
    }

    public static function getAssesmentOfTeacher ($teacher_id) 
    {
        $list = DB::table('assessments')->where('teacher_id', $teacher_id)->get();
        return $list;
    }

    public static function insertAssessment ($data)
    {
        if (is_array($data) && count($data) > 0) {
            $assessment = new Assessment;
            foreach($data as $field => $value) {
                $assessment->$field = $value;
            }
            $assessment->created_at = date('Y-m-d H:i:s');
            return $assessment->save();
        }
        return false;
    }

    public static function updateAssessment ($id, $data) 
    {
        if (is_array($data) && count($data) > 0) {
            $assessment = Assessment::find($id);
            foreach($data as $field => $value) {
                $assessment->$field = $value;
            }
            $assessment->updated_at = date('Y-m-d H:i:s');
            return $assessment->save();
        }
        return false;
    }


}