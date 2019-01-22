<?php

namespace App\Http\Controllers;

use App\Classes;
use App\Invoice;
use App\Student;
use App\StudentClass;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function getInvoiceList (Request $request) 
    {
        $invoices = Invoice::get_list_invoice();
        return response()->json(['code' => 0, 'data' => ['list' => $invoices]], 200);
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
        $class_id = $input['class_id'] ? (int) $input['class_id'] : 0;
        $duration = $input['duration'] ? (int) $input['duration'] : 0;
        $price = isset($input['price']) ? (int) $input['price'] : 0;
        $prepaid = isset($input['prepaid']) ? (int) $input['prepaid'] : 0;
        $discount = isset($input['discount']) ? (int) $input['discount'] : 0;

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

        $amount = ($duration * $price) - $prepaid;

        if ($discount > 0) {
            $amount -= (($amount * $discount) / 100);
        }

        return response()->json(['code' => 0, 'data' => ['duration' => $duration, 'end_date' => $end_date, 'amount' => $amount, 'inputs' => $input]], 200);
    }

    public function save_invoice(Request $request)
    {
        $ary_fields = ['invoice_number',
            'type',
            'reason',
            'student_id',
            'class_id',
            'start_date',
            'end_date',
            'duration',
            'price',
            'payer',
            'reason',
            'amount',
            'prepaid',
            'discount',
            'discount_desc',
            'invoice_status',
            'currency',
            'note',
            'created_by'];

        $input = $request->all();
        $ary_data = [];

        foreach ($ary_fields as $field) {
            if (isset($input[$field])) {
                $ary_data[$field] = $input[$field];
            }
        }

        $ary_data['invoice_number'] = Invoice::invoice_number_generate();
        $ary_data['created_by'] = Auth::user()->id;

        $invoice = new Invoice;

        foreach ($ary_data as $field => $value) {
            $invoice->$field = $value;
        }

        $result = $invoice->save();

        return response()->json(['code' => 0, 'id' => $invoice->id, 'result' => $result, 'data' => $ary_data], 200);
    }

    public function print_invoice($id, $act)
    {
        $invoice = Invoice::findOrfail($id)->toArray();
        $student = Student::findOrfail($invoice['student_id']);
        $class = Classes::findOrfail($invoice['class_id']);
        $user = User::findOrfail($invoice['created_by']);

        $invoice['student_name'] = $student->name;
        $invoice['student_code'] = $student->student_code;
        $invoice['class_name'] = $class->name;
        $invoice['discount'] = (int) $invoice['discount'];
        $invoice['prepaid'] = $invoice['prepaid'] ? $invoice['prepaid'] : 0;
        $invoice['amount_text'] = $invoice['amount'] ? $this->number_to_words($invoice['amount']) : 0;
        $invoice['created_by_name'] = $user->name;

        $invoice['amount'] = number_format ( $invoice['amount'] , 0 , '' , ',' );
        $invoice['prepaid'] = number_format ( $invoice['prepaid'] , 0 , '' , ',' );
        
        $invoice['start_date'] = date('d/m/Y', strtotime($invoice['start_date']));
        $invoice['end_date'] = date('d/m/Y', strtotime($invoice['end_date']));
        $invoice['created_at'] = date('d/m/Y H:i', strtotime($invoice['created_at']));
        $invoice['act'] = $act;

        if ($act == 'print') {
            $clone_invoice = Invoice::find($id);
            if ($clone_invoice->invoice_status == 0) {
                $clone_invoice->invoice_status = 1;
            } else if ($clone_invoice->invoice_status == 1) {
                $clone_invoice->invoice_status = 2;
            }
            $clone_invoice->save();
        }

        $view = ((int)$invoice['type'] == 1) ? 'invoice/detail/tutorfee_print' : 'invoice/detail/otherfee_print';
        
        return view( $view, $invoice);
    }

    protected function number_to_words($number)
    {
        $hyphen = ' ';
        $conjunction = '  ';
        $separator = ' ';
        $negative = 'âm ';
        $decimal = ' phẩy ';
        $dictionary = config('constant.number_text');

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . $this->number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return ucfirst(strtolower($string));
    }
}
