<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Student;
use App\StudentClass;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Create an Invoice
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('invoice/invoice_form');
    }

    public function getStudentList(Request $request)
    {
        $students = Student::all();
        return response()->json(['code' => 0, 'data' => ['list' => $students]], 200);
    }

    public function getClassList(Request $request)
    {
        $input = $request->all();
        $classes = StudentClass::getClassOfStudent($input['student_id']);
        return response()->json(['code' => 0, 'data' => ['list' => $classes, 'input' => $input]], 200);
    }

    public function tuition_calc(Request $request)
    {
        $err_msg = '';

        $input = $request->all();

        $start_date = isset($input['start_date']) ? $input['start_date'] : '';
        $end_date = isset($input['end_date']) ? $input['end_date'] : '';
        $class_id = isset($input['class_id']) ? $input['class_id'] : 0;
        $duration = isset($input['duration']) ? $input['duration'] : 0;

        if ($start_date == '') {
            $err_msg = 'Chưa nhập ngày bắt đầu!';
        } else if ($class_id == 0) {
            $err_msg = 'Chưa chọn lớp!';
        } else if ($end_date == '' && $duration == 0) {
            $err_msg = 'Chưa chọn ngày kết thúc hoặc nhập số buổi học';
        }

        if ($err_msg != '') {
            return response()->json(['code' => 1, 'message' => $err_msg], 200);
        }

        if ($end_date != '' && $duration == 0) {
            $duration = Invoice::calculate_duration($start_date, $end_date, $class_id);
        } else if ($end_date == '' && $duration > 0) {
            $end_date = Invoice::calculate_enddate($start_date, $duration, $class_id);
        }

        $amount = Invoice::calculate_tuition_fee($duration, $class_id);
        return response()->json(['code' => 0, 'data' => ['duration' => $duration, 'end_date' => $end_date, 'amount' => $amount]], 200);
    }
}
