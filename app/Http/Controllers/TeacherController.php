<?php
namespace App\Http\Controllers;

use App\Classes\ZohoCrmConnect;
use App\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $record = $request->record;
        $keyword = $request->keyword;
        $page = $request->page;
        if ($record == "") {
            $record = 10;
        }
        $sum_row = count(Teacher::all());
        $sum_page = ceil($sum_row / $record);
        if ($page > $sum_page || !is_numeric($page)) {
            $page = 1;
        }
        $all = Teacher::Search($keyword, $record, $page);
        //$all = [];
        return response()->json(['code' => 1, 'message' => 'ket qua', 'data' => $all], 200);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = array(
                'name' => $request->name,
                'nationality' => $request->nationality,
                'address' => $request->address,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'birthdate' => $request->birthdate,
                'description' => $request->description,
                'experience' => $request->experience,
                'certificate' => $request->certificate,
                'gender' => $request->gender,
                'created_by' => $request->logged_user->id,
                'created_at' => date("Y-m-d H:i:s"),
            );

            $teacher = Teacher::getTeacher($data['email'], 'email');
            if (count($teacher) == 0) {
                $id = Teacher::insert($data);
            } else {
                return response()->json(['code' => 0, 'message' => 'Giáo viên đã tồn tại', 'data' => $teacher], 200);
            }

            $result = false;

            if ($id) {
                $crm_module = config('zoho.MODULES.ZOHO_MODULE_EMS_TEACHER');
                $crm_mapping = config('zoho.MAPPING.ZOHO_MODULE_EMS_TEACHER');
                $zoho_crm = new ZohoCrmConnect();
                $crm_teacher = $zoho_crm->searchRecordByEmail($crm_module, $data['email']);

                $crm_data = ['data' => []];
                foreach ($crm_mapping as $key => $field) {
                    if ($key == 'EMS_ID') {
                        $crm_data['data'][0][$key] = '' . $id;
                    } else if ($key == 'EMS_SYNC_TIME') {
                        $crm_data['data'][0][$key] = date('Y-m-d H:i:s');
                    } else if ($field != '') {
                        $crm_data['data'][0][$key] = $data[$field];
                    } else {
                        $crm_data['data'][0][$key] = '';
                    }
                }

                if ($crm_teacher == false) {
                    $result = $zoho_crm->upsertRecord($crm_module, $crm_data);
                    if ($result != false) {
                        $crm_id = $result->details->id;
                        $teacher = Teacher::find($id);
                        $teacher->crm_id = $crm_id;
                        $teacher->update();
                    }
                }
            }

            return response()->json(['code' => 1, 'message' => 'Them thanh cong', 'data' => $result], 200);
        } catch (Exception $e) {
            return response()->json(['code' => 0, 'message' => $e->getMessage()], 200);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $id = $request->id;
        if ($id) {
            if (Teacher::find($id) == null) {
                return response()->json(['code' => 0, 'message' => 'khong ton tai giao vien nay'], 200);
            } else {
                $teacher = Teacher::edit1($id);
                return response()->json(['code' => 1, 'data' => $teacher], 200);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $data = array(
                'id' => $request->id,
                'crm_id' => $request->crm_id,
                'name' => $request->name,
                'nationality' => $request->nationality,
                'address' => $request->address,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'birthdate' => $request->birthdate,
                'description' => $request->description,
                'experience' => $request->experience,
                'certificate' => $request->certificate,
                'gender' => $request->gender,
                'updated_at' => date("Y-m-d H:i:s"),
            );

            $teacher = Teacher::find($data['id']);
            $teacher->name = $data['name'];
            $teacher->nationality = $data['nationality'];
            $teacher->address = $data['address'];
            $teacher->mobile = $data['mobile'];
            $teacher->email = $data['email'];
            $teacher->birthdate = $data['birthdate'];
            $teacher->description = $data['description'];
            $teacher->experience = $data['experience'];
            $teacher->gender = $data['gender'];
            $teacher->updated_at = $data['updated_at'];
            $teacher->update();

            $result = false;

            $crm_module = config('zoho.MODULES.ZOHO_MODULE_EMS_TEACHER');
            $crm_mapping = config('zoho.MAPPING.ZOHO_MODULE_EMS_TEACHER');
            $zoho_crm = new ZohoCrmConnect();

            $crm_data = ['data' => [
                0 => [
                    'id' => $data['crm_id'],
                ],
            ]];

            foreach ($crm_mapping as $key => $field) {
                if ($key == 'EMS_ID') {
                    $crm_data['data'][0][$key] = '' . $id;
                } else if ($key == 'EMS_SYNC_TIME') {
                    $crm_data['data'][0][$key] = date('Y-m-d H:i:s');
                } else if ($field != '') {
                    $crm_data['data'][0][$key] = $data[$field];
                } else {
                    $crm_data['data'][0][$key] = '';
                }
            }

            $result = $zoho_crm->upsertRecord($crm_module, $crm_data);
           

            return response()->json(['code' => 1, 'message' => 'Sua thanh cong', 'data' => $result], 200);
        } catch (Exception $e) {
            return response()->json(['code' => 0, 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * Create a function delete teacher
     *
     * @return void
     */
    public function deleteTeacher(Request $request)
    {
        $teacher_id = $request->id;
        if ($teacher_id) {
            if (Teacher::find($teacher_id) == null) {
                return response()->json(['code' => 0, 'message' => 'khong ton tai nhan vien nay'], 200);
            } else {
                Teacher::deleteTeacher($teacher_id);
                return response()->json(['code' => 1, 'message' => 'Xoa thanh cong'], 200);
            }
        }
    }

    private function insertZohoCem($data)
    {

    }
}
