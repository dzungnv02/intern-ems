<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CompensatedClass extends Model
{
    public static function search($condition, $limit = 0, $offset = 0) 
    {
        $ary_fields = [
            'cc.*',
            's.student_code',
            's.name as s_name',
            'c.name as c_name'
        ];

        $fields = implode(',',$ary_fields);

        $sql = 'SELECT ' . $fields . ' FROM compensated_class as cc INNER JOIN students as s ON cc.student_id = s.id INNER JOIN classes as c ON cc.class_id = c.id';

        
        return DB::select($sql);
        
    }
}
