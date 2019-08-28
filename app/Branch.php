<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Branch extends Model
{
    protected $table = 'branch';

    public static function getBranchs($filter = null)
    {
        $query = DB::table('branch')->select('*');
        if ($filter !== null) {
            $query->where($filter);
        }
        return $query->orderBy('city', 'DESC')->get()->toArray();
    }

    public static function getBranch($branch_id)
    {
        return DB::table('branch')->select('*')
            ->where('id', $branch_id)
            ->get()->toArray();
    }

    public static function getBranchByCrmId($crm_id)
    {
        return DB::table('branch')->select('*')
        ->where('crm_id', $crm_id)
        ->get()->toArray();
    }

    public static function getBranchByEmail($email)
    {
        return DB::table('branch')->select('*')
        ->where('email', $email)
        ->orWhere('email_2', $email)
        ->get()->toArray();
    }

    public static function insertBranch(Array $ary_data)
    {
        return DB::table('branch')->insertGetId($ary_data);
    }

    public static function updateBranch($branch_id, $ary_data)
    {
        return DB::table('branch')->where('id', $branch_id)->update($ary_data);
    }

    public static function deleteBranch($branch_id)
    {
        return DB::table('branch')->where('id', $branch_id)->delete();
    }

    public static function getBranchByCrmOwner ($crm_owner_id)
    {
        return DB::table('branch')->select('*')
        ->where('crm_owner', $crm_owner_id)
        ->whereNotNull('crm_owner')
        ->first();
    }

}
