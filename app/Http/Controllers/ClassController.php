<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes;
use App\StudentClass;
use App\TimeTable;
use Validator;
use App\Holiday;
use App\Course;
use DB;
use DateTime;

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
        
        if($recordsPerPage==""){
            $recordsPerPage =10;
        }
        $classesOfList= Classes::getListClass($keyword,$recordsPerPage,$page);

        if($classesOfList->count()==0){
            return response()->json(['code'=>0,'message'=>'Không tìm thấy lớp!'],200);
        }
        else{
            return response()->json(['code'=>1,'data'=>$classesOfList],200);
        }

    }
    /**
     * Xóa lớp học.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteClass(Request $request){
        $idClass = $request->input('id');
        $findIdClass = Classes::getInfoClass($idClass);
        $checkClass = Classes::checkQtyStudentsOfClass($idClass);
        
        if($findIdClass==""){
            $message = [ "code"=>0,"message"=>"Không tìm thấy khóa học cần xóa!"];
            return response()->json($message,200);
        }else if($checkClass==0){
            Classes::deleteClass($idClass);
            Classes::deleteTimeTableOfClass($idClass);
            return response()->json(['code'=>1,'message'=>'Xóa lớp thành công!']);
        }
        else{
            $message = [ "code"=>0,"message"=>"Lớp này đang hoạt động không thể xóa! "];
            return response()->json($message,200);
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
        $errors = Validator::make($request->all(),
            [
                        'class_code'=>'required|unique:classes',
                        'name'      =>'required',
                        'teacher_id'=>'required|numeric',
                        'time_start'=>'required',
                        'duration'  =>'required|numeric',
                        'class_size'=>'required|numeric',
                        'course_id' =>'required|numeric'
            ],
            [
                        'class_code.required'  =>"Mã lớp học không được trống!",
                        'class_code.unique'    =>"Mã lớp học đã tồn tại!",
                        'name.required'        =>"Tên lớp học không được trống! ",
                        'teacher_id.required'  =>"Bạn chưa chọn giảng viên!",
                        'time_start.required'  =>"Thời gian bắt đầu không được trống!",
                        'time_start.date'      =>"Thời gian bắt đầu không đúng định dạng!",
                        'duration.required'    =>"Thời lượng không được trống!",
                        'duration.numeric'     =>"Thời lượng phải là kiểu số ",
                        'class_size.required'  =>"Sĩ số không được trống!",
                        'class_size.numeric'   =>"Sĩ số phải là kiểu số!",
                        'course_id.required'   =>"Bạn chưa chọn khóa học!"
            ]
        );
        if($errors->fails() || $request->start_date<date('Y-m-d H:i:s')){
            $arrayErrors = $errors->errors()->all();
            $message = [ "code"=>0,"message" =>$arrayErrors];
            return response()->json($message,200);
        }else{
            Classes::createClass($request);
            $timestart = $request->start_date;
            $time_start = $request->start_date;
            $holiday = [];
            $items = Holiday::getListHoliday();
            foreach ($items as $holi) {
                $holiday[] = $holi->holiday;
            };
            $qtyLesson=round((Classes::getDurationOfCourse($request->class_code))
                        /($request->duration));
            $schedule = explode(',',$request->schedule);
            $start=1;
            for($j=0;$j<count($holiday);$j++){
                if($time_start == $holiday[$j]){
                    $timestart = date('Y-m-d', strtotime('+1 day', strtotime($time_start)));
                        return response()->json(['code'=>0,'message'=>"Ngày bắt đầu trùng ngày lễ"]);

                }
            };
            while($start<=$qtyLesson)
            {
            for($i=0;$i<count($schedule);$i++)
                {


                     if(date('w', strtotime($timestart)) == $schedule[$i]){
                        $date[$start]=$timestart;
                          $start ++;
                    }
                }
                $nextdate=date('Y-m-d', strtotime('+1 day', strtotime($timestart)));
                for($j=0;$j<count($holiday);$j++){
                    if($nextdate == $holiday[$j]){
                    $nextdate=date('Y-m-d', strtotime('+2 day', strtotime($timestart)));
                    }
                    $timestart = $nextdate;
                }
            }
            $dataTimeTable=array();
            for($i=1;$i<=$qtyLesson;$i++){
               $dataTimeTable['week_days'] = date('w', strtotime($date[$i]));
               $dataTimeTable['date'] = $date[$i];
               $dataTimeTable['time'] = $request->time_start;
               Classes::createTimeTable($dataTimeTable,$request->class_code);
              $dataTimeTable=array();
            }
            return response()->json(['code'=>1,'message'=>TimeTable::all()]);

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
        $idClass= $request->id;
        $errors = Validator::make($request->all(),
           [
                        'class_code'=>'required|unique:classes,class_code,'.$idClass,
                        'name'      =>'required',
                        'class_size'=>'required|numeric',
            ],
            [
                        'class_code.required'  =>"Mã lớp học không được trống!",
                        'class_code.unique'    =>"Mã lớp học đã tồn tại!",
                        'name.required'        =>"Tên lớp học không được trống! ",
                        'class_size.required'  =>"Sĩ số không được trống!",
                        'class_size.numeric'   =>"Sĩ số phải là kiểu số!",
            ]
        );
        if($errors->fails()){
            $arrayErrors = $errors->errors()->all();
            return response()->json([ "code"=>0,"message" =>$arrayErrors],200);
        }else{
            Classes::editClass($request,$idClass);
            return response()->json(['code'=>1,'message'=>'Chỉnh lớp học thành công!']);
        }
    }
    /**
     * Lấy thông tin lớp học cần sửa
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEditClass(Request $request){
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
        $class_id = $request->input('class_id');
        $studentsOfClass= Classes::getStudentOfClass($class_id);

        if($studentsOfClass->count()==0){
            return response()->json(['code'=>0,'message'=>'Lớp không có học sinh nào!'],200);
        }
        else{
            return response()->json(['code'=>1,'data'=>$studentsOfClass],200);
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
        $idStudent = $request->input('id');
        $findIdStudentInClass = Classes::findStudentOfClass($idStudent);

        if($findIdStudentInClass!=0){
            Classes::deleteStudentOfClass($idStudent);
            $message = [ "code"=>1,"message"=>"Xóa học sinh trong lớp thành công! "];
            return response()->json($message,200);
        }else{
            $message = [ "code"=>0,"message"=>"Không tìm thấy học sinh cần xóa!"];
            return response()->json($message,200);
        }
    }
    /**
     * Lấy tên của giáo viên.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getNameTeacher(){
        $nameTeacher =Classes::getNameTeacher();
        return response()->json(['code'=>1,'data'=> $nameTeacher],200);
    }
    /**
     * Lấy tên các khóa học.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getNameCourse(){
        $nameCourse =Classes::getNameCourse();
        return response()->json(['code'=>1,'data'=> $nameCourse],200);
    }

    /**
     * Danh sách lớp đang tuyển sinh.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getListClassByStatus(Request $request){
        $class = Classes::classByStatus();
        return response()->json(['code' => 1, 'message' => 'ket qua','data' => $class]);
    }

    /**
     * Thêm học sinh vào lớp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addStudentToClass(Request $request){
        $student_id = request('student_id');
        $class_id = request('class_id');
        $data = array(
            'student_id' => $student_id,
            'class_id' => $class_id,
            'created_at' => date("Y-m-d"),
            'updated_at' => date("Y-m-d"),
        );

        $tkb_student = DB::table('students')->join('student_classes','student_classes.student_id','=','students.id')
            ->join('classes','classes.id','=','student_classes.class_id')
            ->join('timetables','timetables.class_id','=','classes.id')
            ->select('classes.start_date',DB::raw('max(timetables.date) as end_date'))
            ->where('students.id',$student_id)
            ->groupBy('classes.start_date')->get();

        $end_date = json_decode(json_encode($tkb_student), True);

        $lop_moi = DB::table('timetables')->join('classes','classes.id','=','timetables.class_id')
            ->select('classes.start_date',DB::raw('max(timetables.date) as end_date'))
            ->where('classes.id',$class_id)->groupBy('classes.start_date')->get();

        $end_date_lop_moi = json_decode(json_encode($lop_moi), True);

        $get = DB::table('students')
            ->join("student_classes", 'students.id', '=' , 'student_classes.student_id')
            ->join('classes', 'classes.id', '=', 'student_classes.class_id')
            ->join('timetables', 'timetables.class_id', '=', 'classes.id')
            ->where('students.id',$student_id)
            ->select('timetables.time', 'timetables.week_days','classes.duration')
            ->distinct('timetables.time', 'timetables.week_days','classes.duration')
            ->get();

        $gio_hoc = json_decode(json_encode($get), True);

        $themvao = DB::table('timetables')->join('classes','classes.id','=','timetables.class_id')
            ->select('timetables.time','timetables.week_days','classes.duration')
            ->where('class_id',$class_id)
            ->distinct('timetables.time', 'timetables.week_days','classes.duration')
            ->get();
        $time_lop_moi = json_decode(json_encode($themvao), True);

        $fail =0;
        for($i=0;$i<count($end_date);$i++){
            if($end_date_lop_moi[0]['start_date']>$end_date[$i]['end_date']||$end_date[$i]['start_date']>$end_date_lop_moi[0]['end_date']){
            }else{
                for($i=0;$i<count($gio_hoc);$i++){
                    for($j=0;$j<count($time_lop_moi);$j++){
                        if($gio_hoc[$i]['week_days']==$time_lop_moi[$j]['week_days']){
                          $time_end1 = date(' H:i:s',strtotime('+'.$gio_hoc[$i]['duration'].'hour',strtotime($gio_hoc[$i]['time'])));
                          $time_end2 = date(' H:i:s',strtotime('+'.$time_lop_moi[$j]['duration'].'hour',strtotime($time_lop_moi[$j]['time'])));
                          $time_start1 = $gio_hoc[$i]['time'];
                          $time_start2 = $time_lop_moi[$j]['time'];
                          if(strtotime($time_end2)<strtotime($time_start1) || strtotime($time_start2) >strtotime($time_end1)){
                          }else{
                              $fail++;
                          }
                        }
                    }
                }
            }
        }

        if ($fail != 0) {
            return response()->json(['code' => 0, 'message' => 'Lịch học bị trùng hoặc Học viên đã có trong lớp']);
        }else{
            $add_student = Classes::addStudentToClass1($data);
            return response()->json(['code' => 1, 'message' => 'Thêm thành công!']);
        }
    }

    /**
     * Cập nhật trạng thái lớp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateClassStatus(Request $request){
        $id = request('id');
        $data = array(
            'status' =>request('status'),
        );
        $class = Classes::updateClassStatus1($data,$id);
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
        $class_id= $request->input('class_id');
        $studentsNotInClass = DB::table('students')->whereNotIn('id', DB::table('student_classes')->select('student_id')->from('student_classes')->where('class_id',$class_id)
           ) ->get();
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
        $end = DB::table('timetables')->join('classes','classes.id','=','timetables.class_id')
        ->selectRaw("MAX(timetables.date) AS end_date, MIN(timetables.date) AS start_date, timetables.class_id,classes.status")
        ->groupBy('class_id')->get();
        $a = json_decode(json_encode($end), True);
        for ($i=0; $i < count($a); $i++) {
            if ($a[$i]['status'] != 3) {
                if (strtotime($date) > strtotime($a[$i]['end_date'])) {
                    $status = 2;
                }elseif(strtotime($date) < strtotime($a[$i]['start_date'])){
                    $status = 0;
                }else{
                    $status = 1;
                }
                $s = DB::table('classes')->where('id',$a[$i]['class_id'])->update(['status' =>$status]);
            }
        }

        return response()->json(['code' => 1, 'message' => 'update thanh cong']);
    }
}
