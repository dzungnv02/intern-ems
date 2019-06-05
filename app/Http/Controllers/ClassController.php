<?php

namespace App\Http\Controllers;

use App\Classes\ZohoCrmConnect;
use DB;
use Illuminate\Http\Request;
use \App\Branch;
use \App\Classes;
use \App\Student;
use \App\Teacher;
use \Illuminate\Support\Facades\Log;

class ClassController extends Controller
{
    /**
     * hiển thị danh sách lớp đang tuyển sinh.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function getListClass(Request $request)
    {
        $keyword = $request->input('keyword');
        $recordsPerPage = $request->input('rec');
        $page = $request->input('page');

        if ($recordsPerPage == "") {
            $recordsPerPage = 10;
        }
        $classesOfList = Classes::getListClass($keyword, $recordsPerPage, $page);

        if ($classesOfList->count() == 0) {
            return response()->json(['code' => 0, 'message' => 'Không tìm thấy lớp!'], 200);
        } else {
            return response()->json(['code' => 1, 'data' => $classesOfList], 200);
        }

    }
    /**
     * Xóa lớp học.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteClass(Request $request)
    {
        $id = $request->input('id');
        $findIdClass = Classes::getInfoClass($id);
        $checkClass = Classes::checkQtyStudentsOfClass($id);

        if ($findIdClass == "") {
            $message = ["code" => 0, "message" => "Không tìm thấy khóa học cần xóa!"];
            return response()->json($message, 200);
        } else if ($checkClass == 0) {
            Classes::deleteClass($id);
            Classes::deleteTimeTableOfClass($id);
            return response()->json(['code' => 1, 'message' => 'Xóa lớp thành công!']);
        } else {
            $message = ["code" => 0, "message" => "Lớp này đang hoạt động không thể xóa! "];
            return response()->json($message, 200);
        }

    }

    /**
     * Tạo mới lớp học.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createClass(Request $request)
    {
        try {

            $inputs = $request->all();
            //validate
            $data = [];
            if (is_array($inputs)) {
                foreach ($inputs as $field => $value) {
                    if ($field != 'schedule' && $field != 'logged_user') {
                        $data[$field] = $value;
                    }
                }

                $data['schedule'] = json_encode($inputs['schedule'], JSON_UNESCAPED_UNICODE);
                $data['branch_id'] = $inputs['logged_user']->branch;
                $data['created_at'] = date(('Y-m-d H:i:s'));

                $class_id = Classes::insertOne($data);

                if ($class_id) {
                    $crm_module = config('zoho.MODULES.ZOHO_MODULE_EMS_CLASS');
                    $crm_mapping = config('zoho.MAPPING.ZOHO_MODULE_EMS_CLASS');
                    $zoho_crm = new ZohoCrmConnect();
                    //$crm_class = $zoho_crm->search($crm_module, 'Product_Name', $data['name']);
                    $crm_class = false;

                    $teacher = Teacher::find($data['teacher_id']);
                    $branch = Branch::find($data['branch_id']);

                    $crm_data = ['data' => []];
                    $crm_data['data'][0]['EMS_ID'] = trim($class_id);
                    $crm_data['data'][0]['EMS_SYNC_TIME'] = date('Y-m-d H:i:s');
                    $crm_data['data'][0]['teacher'] = ['id' => $teacher->crm_id, 'name' => $teacher->name];
                    $crm_data['data'][0]['Product_Name'] = $data['name'];
                    $crm_data['data'][0]['course_name'] = $data['course_name'];
                    $crm_data['data'][0]['Product_Active'] = $data['status'] == 2 ? true : false;
                    $crm_data['data'][0]['Owner'] = ['id' => $branch->crm_owner_id, 'name' => $branch->crm_owner_name];

                    if (is_array($inputs['schedule'])) {
                        $ary_wd = array_keys($inputs['schedule']);
                        $ary_hours = array_values($inputs['schedule']);
                        $crm_data['data'][0]['L_ch_h_c_trong_tu_n'] = [0 => [
                            'weekday_1' => isset($ary_wd[0]) ? ucfirst($ary_wd[0]) : '',
                            'time_1' => isset($ary_hours[0]) ? implode(' - ', $ary_hours[0]) : '',
                            'weekday_2' => isset($ary_wd[1]) ? ucfirst($ary_wd[1]) : '',
                            'time_2' => isset($ary_hours[0]) ? implode(' - ', $ary_hours[1]) : '',
                        ],
                        ];
                    }

                    if ($crm_class == false) {
                        $result = $zoho_crm->upsertRecord($crm_module, $crm_data);
                        if ($result != false) {
                            $crm_id = $result->details->id;
                            $class = Classes::find($class_id);
                            $class->crm_id = $crm_id;
                            $class->update();
                        }
                    }
                }
            }

            return response()->json(['code' => 1, 'data' => $class_id, 'message' => 'Tạo lớp học thành công!']);
        } catch (Exception $e) {
            return response()->json(['code' => 0, 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * Chỉnh sửa lớp học.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editClass(Request $request)
    {
        try {

            $inputs = $request->all();
            $data = [];
            if (is_array($inputs)) {
                $id = $inputs['id'];
                foreach ($inputs as $field => $value) {
                    if ($field != 'schedule' && $field != 'logged_user' && $field != 'id') {
                        $data[$field] = $value;
                    }
                }

                $data['schedule'] = json_encode($inputs['schedule'], JSON_UNESCAPED_UNICODE);
                $data['branch_id'] = $inputs['logged_user']->branch;
                $data['updated_at'] = date(('Y-m-d H:i:s'));

                $result = Classes::updateOne($id, $data);
                $class = Classes::find($id);

                if ($result) {
                    $crm_module = config('zoho.MODULES.ZOHO_MODULE_EMS_CLASS');
                    $crm_mapping = config('zoho.MAPPING.ZOHO_MODULE_EMS_CLASS');
                    $zoho_crm = new ZohoCrmConnect();
                    $crm_class = $zoho_crm->getRecordById($crm_module, $class->crm_id);

                    $teacher = Teacher::find($data['teacher_id']);
                    $branch = Branch::find($data['branch_id']);
                    $crm_schedule_id = null;

                    $crm_data = ['data' => []];
                    $crm_data['data'][0]['id'] = $class->crm_id;
                    $crm_data['data'][0]['EMS_ID'] = trim($id);
                    $crm_data['data'][0]['EMS_SYNC_TIME'] = date('Y-m-d H:i:s');
                    $crm_data['data'][0]['teacher'] = $teacher ? ['id' => $teacher->crm_id, 'name' => $teacher->name] : null;
                    $crm_data['data'][0]['Product_Name'] = $data['name'];
                    $crm_data['data'][0]['course_name'] = $data['course_name'];
                    $crm_data['data'][0]['Product_Active'] = $data['status'] == 2 ? true : false;
                    $crm_data['data'][0]['Owner'] = ['id' => $branch->crm_owner_id, 'name' => $branch->crm_owner_name];

                    if ($crm_class != false) {
                        $crm_schedule_id = isset($crm_class->L_ch_h_c_trong_tu_n) ? $crm_class->L_ch_h_c_trong_tu_n[0]->id : null;
                    }

                    if (is_array($inputs['schedule'])) {
                        $ary_wd = array_keys($inputs['schedule']);
                        $ary_hours = array_values($inputs['schedule']);
                        $crm_data['data'][0]['L_ch_h_c_trong_tu_n'] = [0 => [
                            'weekday_1' => isset($ary_wd[0]) ? ucfirst($ary_wd[0]) : '',
                            'time_1' => isset($ary_hours[0]) ? implode(' - ', $ary_hours[0]) : '',
                            'weekday_2' => isset($ary_wd[1]) ? ucfirst($ary_wd[1]) : '',
                            'time_2' => isset($ary_hours[0]) ? implode(' - ', $ary_hours[1]) : '',
                        ],
                        ];

                        if ($crm_schedule_id != null) {
                            $crm_data['data'][0]['L_ch_h_c_trong_tu_n'][0]['id'] = $crm_schedule_id;
                        }
                    }

                    Log::info(json_encode($crm_data));
                    $result = $zoho_crm->upsertRecord($crm_module, $crm_data);
                }

                return response()->json(['code' => 1, 'data' => $result, 'message' => 'Cập nhật lớp học thành công!']);
            } else {
                return response()->json(['code' => 0, 'message' => 'Error!']);
            }
        } catch (Exception $e) {
            return response()->json(['code' => 0, 'message' => $e->getMessage()], 200);
        }
    }
    /**
     * Lấy thông tin lớp học cần sửa
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEditClass(Request $request)
    {
        $idClass = $request->input('id');
        $infoClassNeedEdit = Classes::getEditClass($idClass);
        return $infoClassNeedEdit;

    }
    /**
     * Lấy danh sách học sinh của lớp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getListStudentOfClass(Request $request)
    {
        $class_id = $request->input('id');
        $studentsOfClass = Classes::getStudentOfClass($class_id);

        if ($studentsOfClass->count() == 0) {
            return response()->json(['code' => 0, 'message' => 'Lớp không có học sinh nào!'], 200);
        } else {
            return response()->json(['code' => 1, 'data' => $studentsOfClass], 200);
        }
    }
    /**
     * Xóa học sinh của lớp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteStudentOfClass(Request $request)
    {
        try {
            $student_id = $request->input('student_id');
            $class_id = $request->input('class_id');
            $findIdStudentInClass = Classes::findStudentOfClass($class_id, $student_id);

            if ($findIdStudentInClass != 0) {
                Classes::deleteStudentOfClass($class_id, $student_id);

                $class = Classes::find($class_id);
                $crm_module = config('zoho.MODULES.ZOHO_MODULE_STUDENTS');
                $zoho_crm = new ZohoCrmConnect();
                $crm_class = $zoho_crm->getRecordById($crm_module, $class->crm_id);
                $student = Student::find($student_id);

                $crm_data = ['data' => []];
                $crm_data['data'][0] = [
                    'id' => $student->crm_id,
                    'L_p_EMS' => null,
                ];

                $result = $zoho_crm->upsertRecord($crm_module, $crm_data);

                $message = ["code" => 1, "message" => "Xóa học sinh trong lớp thành công! "];
                return response()->json($message, 200);
            } else {
                $message = ["code" => 0, "message" => "Không tìm thấy học sinh cần xóa!"];
                return response()->json($message, 200);
            }
        } catch (Exception $e) {
            return response()->json(['code' => 0, 'message' => $e->getMessage()], 200);
        }
    }
    /**
     * Lấy tên của giáo viên.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getNameTeacher()
    {
        $nameTeacher = Classes::getNameTeacher();
        return response()->json(['code' => 1, 'data' => $nameTeacher], 200);
    }
    /**
     * Lấy tên các khóa học.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getNameCourse()
    {
        $nameCourse = Classes::getNameCourse();
        return response()->json(['code' => 1, 'data' => $nameCourse], 200);
    }

    /**
     * Danh sách lớp đang tuyển sinh.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getListClassByStatus(Request $request)
    {
        $class = Classes::classByStatus();
        return response()->json(['code' => 1, 'message' => 'ket qua', 'data' => $class]);
    }

    /**
     * Thêm học sinh vào lớp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addStudentToClass(Request $request)
    {
        $input = $request->all();
        $class_id = $input['class_id'];
        $students = $input['students'];
        $data = [];
        if (is_array($students)) {
            foreach ($students as $student_id) {
                $data[] = ['class_id' => $class_id, 'student_id' => $student_id, 'created_at' => date("Y-m-d H:i:s")];
            }
        }

        try {
            $result = Classes::addStudentToClass1($data);
            $class = Classes::find($class_id);

            if ($result) {
                $crm_module = config('zoho.MODULES.ZOHO_MODULE_STUDENTS');
                $crm_mapping = config('zoho.MAPPING.ZOHO_MODULE_STUDENTS');
                $zoho_crm = new ZohoCrmConnect();
                $student_list = Student::find($students)->toArray();
                $crm_data = ['data' => []];

                foreach ($student_list as $student) {
                    $crm_data['data'][] = [
                        'id' => $student['crm_id'],
                        'Deal_Name' => $student['name'],
                        'L_p_EMS' => [
                            'id' => $class->crm_id,
                            'name' => $class->name,
                        ],
                    ];
                }
                Log::info(json_encode($crm_data, JSON_UNESCAPED_UNICODE));
                $result = $zoho_crm->upsertRecord($crm_module, $crm_data);
            }

            return response()->json(['code' => 1, 'crm_data' => $crm_data, 'message' => 'Thêm thành công!']);
        } catch (Exception $e) {
            return response()->json(['code' => 0, 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * Cập nhật trạng thái lớp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateClassStatus(Request $request)
    {
        $id = request('id');
        $data = array(
            'status' => request('status'),
        );
        $class = Classes::updateClassStatus1($data, $id);
        return response()->json(['code' => 1, 'message' => 'cap nhat thanh cong']);
    }

    /**
     * Danh sách học sinh không có trong lớp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getListStudentNotInClass(Request $request)
    {
        $class_id = $request->input('id');
        $studentsNotInClass = DB::table('students')
            ->whereNotIn('id', DB::table('student_classes')
                    ->select('student_id')
                    ->from('student_classes')
                    ->where('class_id', $class_id)
            )->where('students.name', '!=', null)
            ->get();
        return response()->json(['code' => 1, 'data' => $studentsNotInClass]);
    }

    /**
     * Danh sách học sinh không có trong lớp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function autoUpdateStatus(Request $request)
    {
        $date = date('Y-m-d');
        $end = DB::table('timetables')->join('classes', 'classes.id', '=', 'timetables.class_id')
            ->selectRaw("MAX(timetables.date) AS end_date, MIN(timetables.date) AS start_date, timetables.class_id,classes.status")
            ->groupBy('class_id')->get();
        $a = json_decode(json_encode($end), true);
        for ($i = 0; $i < count($a); $i++) {
            if ($a[$i]['status'] != 3) {
                if (strtotime($date) > strtotime($a[$i]['end_date'])) {
                    $status = 2;
                } elseif (strtotime($date) < strtotime($a[$i]['start_date'])) {
                    $status = 0;
                } else {
                    $status = 1;
                }
                $s = DB::table('classes')->where('id', $a[$i]['class_id'])->update(['status' => $status]);
            }
        }

        return response()->json(['code' => 1, 'message' => 'update thanh cong']);
    }
}
