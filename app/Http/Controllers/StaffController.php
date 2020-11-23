<?php
namespace App\Http\Controllers;

use App\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    /**
     * Create a function getListStaff.
     *
     * @return void
     */
    public function getListStaff(Request $request)
    {
        $json = [
            'code' => 1,
            'message' => 'Success',
            'data' => Staff::where('is_disabled', 0)->get(),
        ];
        return response()->json($json, 200);
    }

    /**
     * Create a function deleteStaff
     *
     * @return void
     */
    public function deleteStaff(Request $request)
    {
        $staff_id = $request->id;
        if ($staff_id) {
            if (Staff::find($staff_id) == null) {
                return response()->json(['code' => 0, 'message' => 'khong ton tai nhan vien nay'], 200);
            } else {
                Staff::deleteStaff($staff_id);
                return response()->json(['code' => 1, 'message' => 'Xoa thanh cong'], 200);
            }
        }
    }

    /**
     * Create a function addStaff
     *
     * @return void
     */
    public function addStaff(Request $request)
    {
        $dataStaff = array(
            'email' => $request->email,
            'password' => $request->password,
            'name' => $request->name,
            'role' => $request->role,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'branch_id' => $request->branch_id,
            'created_at' => date("Y-m-d"),
        );

        $data = Staff::addStaff($dataStaff);
        return response()->json(['code' => 1, 'message' => 'Them thanh cong'], 200);
    }
    /*
     * edit password staff
     **/
    public function editPasswordStaff(Request $request)
    {
        $id = $request->id;
        $newPassword = $request->newPassword;
        $editStaff = Staff::editPasswordStaff($id, $newPassword);
        return response()->json(['code' => 1, 'message' => 'Cap nhat thanh cong'], 200);
    }

    public function verifyPassword($password)
    {

        return response()->json(['code' => 1, 'matched' => Staff::checkCurrentPassword(base64_decode($password), Auth::user()->id)], 200);
    }

    /*
     * edit staff_id
     */
    public function editStaff(Request $request)
    {
        $id = $request->id;
        $data = array(
            'email' => $request->email,
            'name' => $request->name,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'updated_at' => date("Y-m-d H:i:s"),
        );

        $result = Staff::editStaff($data, $id);
        return response()->json(['code' => 1, 'message' => 'Cap nhat thanh cong', 'data' => $result], 200);
    }
}
