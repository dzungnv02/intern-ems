<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class Staff extends Model
{
    protected $table = 'staffs';
    /**

     * Create a function addStaff
     *
     * @return void
     */
    public static function addStaff($data)
    {
        $staff = DB::table('staffs')->insert([
            ['name' => $data['name'],
                'role' => $data['role'],
                'email' => $data['email'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'phone_number' => $data['phone_number'],
                'birth_date' => $data['birth_date'],
                'branch_id' => $data['branch_id'],
                'created_at' => $data['created_at'],
                'password' => bcrypt($data['password']),
            ],
        ]);
        return $staff;
    }

    public static function search($keyword, $record_per_page, $page = 1, $start = 0)
    {
        //$start = ($page - 1) * $record_per_page;
        $search = DB::table('staffs')
            ->leftJoin('branch', 'branch.id', '=', 'staffs.branch_id')
            ->select('staffs.id', 'staffs.email', 'staffs.name', 'staffs.role', 'staffs.branch_id', 'staffs.birth_date', 'staffs.gender', 'staffs.address', 'staffs.phone_number', 'branch.branch_name')
            ->orderBy('staffs.id', 'desc')
            ->where('is_disabled', 0)
            ->orwhere('staffs.name', 'like', '%' . $keyword . '%')
            ->orwhere('staffs.email', 'like', '%' . $keyword . '%')
            ->orwhere('staffs.address', 'like', '%' . $keyword . '%')
            ->orwhere('staffs.phone_number', 'like', '%' . $keyword . '%')
            ->offset($start)->limit($record_per_page)->get();
        return $search;
    }

    public static function deleteStaff($staff_id)
    {
        return DB::table('staffs')->where('id', $staff_id)->update(['is_disabled' => 1]);
        //return DB::table('staffs')->where('id', $staff_id)->delete();
    }

    public static function editPasswordStaff($id, $newPassword)
    {
        $staff = DB::table('staffs')
            ->where('id', $id)
            ->update([
                'password' => bcrypt($newPassword),
            ]);
        return $staff;
    }

    public static function editStaff($data, $id)
    {
        $staff = DB::table('staffs')
            ->where('id', $id)
            ->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'gender' => $data['gender'],
                'birth_date' => $data['birth_date'],
                'address' => $data['address'],
                'phone_number' => $data['phone_number'],
                'updated_at' => $data['updated_at'],
            ]);
        return $staff;
    }

    public static function checkCurrentPassword ($password, $id)
    {
        $staff = Staff::find($id);

        if (Hash::check($password, $staff->password)) {
            return true;
        }
        else {
            return false;
        }
    }
}
