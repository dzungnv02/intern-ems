<?php
namespace App\Http\Controllers;

use App\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Create a function getListStaff.
     *
     * @return void
     */
    public function getListStaff(Request $request)
    {
        $record_per_page = $request->record;
        $keyword = $request->keyword;
        $page = $request->page;
        if ($record_per_page == "") {
            $record_per_page = 10;
        }
        $sum_row = count(Staff::all());
        $sum_page = ceil($sum_row / $record_per_page);
        if ($page > $sum_page || !is_numeric($page)) {
            $page = 1;
        }
        $all = Staff::Search($keyword, $record_per_page, $page);
        return response()->json(['code' => 1, 'message' => 'ket qua', 'data' => $all], 200);
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
        $currentPassword = $request->currentPassword;
        $newPassword = $request->newPassword;
        $editStaff = Staff::editPasswordStaff($id, $currentPassword, $newPassword);
        return response()->json(['code' => 1, 'message' => 'Cap nhat thanh cong'], 200);
    }
    /*
     * edit staff_id
     */
    public function editStaff(Request $request)
    {
        $id = $request->id;

        if (($request->hasFile('file'))) {
            $destinationPath = 'storage/files';
            $extension = $request->file('file')->getClientOriginalExtension();
            $tempName = $request->file("file")->getClientOriginalName();
            $fileName = uniqid("MW") . '.' . $extension;
            $request->file('file')->move($destinationPath, $fileName);
            $imagepath = $destinationPath . '/' . $fileName;
        }
        $data = array(
            'email' => $request->email,
            'password' => $request->password,
            'name' => $request->name,
            'gender' => $request->gender,
            'birthDate' => $request->birthDate,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'images' => $fileName,
            'updated_at' => date("Y-m-d"),
        );
        $data = Staff::editStaff($data, $id);
        return response()->json(['code' => 1, 'message' => 'Cap nhat thanh cong'], 200);
    }
}
