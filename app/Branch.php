<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Branch extends Model
{
    public static function getBranchs() {
        return DB::table('branch')->select('*')->get()->toArray();
    }

    public static function getBranch($branch_id) {
        return DB::table('branch')->select('*')
        ->where('id', $branch_id)
        ->get()->toArray();
    }

    public static function insertBranch ($ary_data) {
        return DB::table('branch')->insertGetId($ary_data);
    }

    public static function updateBranch ($branch_id, $ary_data) {
        return DB::table('branch')->where('id', $branch_id)->update($ary_data);
    }

    public static function deleteBranch ($branch_id) {
        return DB::table('branch')->where('id', $branch_id)->delete();
    }

}
