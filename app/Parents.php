<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Parents extends Model
{
    protected $table = 'parents';

    public static function insertOne($record)
    {
        return DB::table('parents')->insertGetId($record);
    }

    public static function updateOne($id, $data)
    {
        return DB::table('parents')->where('id', $id)->update($data);
    }

    public static function getParents()
    {
        return DB::table('parents')->select('*')->get()->toArray();
    }

    public static function getParent($search_value, $field = 'id')
    {
        return DB::table('parents')
                ->select('*')
                ->where($field, $search_value)
                ->get()->toArray();
    }
}
