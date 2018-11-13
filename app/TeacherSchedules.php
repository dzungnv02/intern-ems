<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class TeacherSchedules extends Model
{
    public static function insert ($data)
    {
        
        Log::info('insert Table schedule');
        
    }
}
