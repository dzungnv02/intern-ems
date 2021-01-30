<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Classes;
use App\Invoice;
use App\Mail\InvoicePrinted;
use App\Parents;
use App\Student;
use App\StudentClass;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel as Excel;
use App\Exports\InvoiceExport;

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

    public function getInvoiceList(Request $request)
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
        $discount_type = isset($input['discount_type']) ? $input['discount_type'] : 'p';

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

        $amount = ($duration * $price);

        if ($discount > 0 && $discount_type === 'p') {
            $amount -= (($amount * $discount) / 100);
        } else if ($discount > 0 && $discount_type === 'c') {
            $amount -= $discount;
        }

        $amount -= $prepaid;

        return response()->json(['code' => 0, 'data' => ['duration' => $duration, 'end_date' => $end_date, 'amount' => $amount, 'inputs' => $input]], 200);
    }

    public function save_invoice(Request $request)
    {
        $branch = Branch::findOrfail($request->logged_user->branch);

        $ary_fields = ['invoice_number',
            'type',
            'reason',
            'student_id',
            'payment_method',
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
            'discount_type',
            'invoice_status',
            'currency',
            'note',
            'created_by'];

        $input = $request->all();

        $ary_data = [];
        $input['amount'] = !$input['amount'] ? 0 : $input['amount'];
        foreach ($ary_fields as $field) {
            if (isset($input[$field])) {
                $ary_data[$field] = $input[$field];
            }
        }

        $ary_data['invoice_number'] = Invoice::invoice_number_generate($branch->branch_code);
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

        //Log::debug('PRINTED - ID: '. $id . ' - act: . $act');

        try {
            $payment_methods = config('constant.payment_method');
            $invoice = Invoice::findOrfail($id);
            //Log::debug(var_export($invoice, true));

            $invoice_data = $invoice->toArray();

            $student = Student::findOrfail($invoice_data['student_id']);

            if ($invoice_data['class_id']) {
                $class = Classes::findOrfail($invoice_data['class_id']);
            }
            else {
                $class = null;
            }

            $user = User::findOrfail($invoice_data['created_by']);

            $resource_path = dirname(app_path()) . '/public/';

            $ary_css_files = [
                $resource_path . 'admin/bootstrap/css/bootstrap.min.css',
                $resource_path . 'admin/font-awesome/css/font-awesome.min.css',
                $resource_path . 'admin/Ionicons/css/ionicons.min.css',
                $resource_path . 'admin/css/AdminLTE.css',
            ];

            $css = '';

            foreach ($ary_css_files as $file) {
                $css .= file_get_contents($file) . "\n";
            }

            $last_printed_time = date('Y-m-d H:i:s');

            $invoice_data['css'] = $css;

            $invoice_data['student_name'] = $student->name;
            $invoice_data['student_code'] = $student->student_code;
            $invoice_data['payment_method'] = $payment_methods[$invoice_data['payment_method']];

            $invoice_data['class_name'] = $class !== null ? $class->name : '';
            $invoice_data['discount'] = $invoice_data['discount_type'] === 'p' ? (int) $invoice_data['discount'] . '%' : number_format($invoice_data['discount'], 0, '', ',') . ' ' . $invoice_data['currency'];
            $invoice_data['prepaid'] = $invoice_data['prepaid'] ? $invoice_data['prepaid'] : 0;
            $invoice_data['amount_text'] = $invoice_data['amount'] ? $this->number_to_words($invoice_data['amount']) : 0;
            $invoice_data['created_by_name'] = $user->name;

            $invoice_data['amount'] = number_format($invoice_data['amount'], 0, '', ',');
            $invoice_data['prepaid'] = number_format($invoice_data['prepaid'], 0, '', ',');

            if ($invoice_data['last_printed_time'] == null && $invoice_data['invoice_status'] > 0) {
                $invoice_data['last_printed_time'] = $invoice_data['updated_at'];
            } else if ($invoice_data['last_printed_time'] == null && $invoice_data['invoice_status'] == 0) {
                $invoice_data['last_printed_time'] = 'Chưa in';
            }

            if ($act == 'print') {
                $invoice_data['last_printed_time'] = $last_printed_time;
            }

            $invoice_data['start_date'] = date('d/m/Y', strtotime($invoice_data['start_date']));
            $invoice_data['end_date'] = date('d/m/Y', strtotime($invoice_data['end_date']));
            $invoice_data['created_at'] = date('d/m/Y H:i', strtotime($invoice_data['created_at']));
            $invoice_data['act'] = $act;
            $view = ((int) $invoice_data['type'] == 1) ? 'invoice/detail/tutorfee_print' : 'invoice/detail/otherfee_print';
            $view_pdf = ((int) $invoice_data['type'] == 1) ? 'invoice/detail/tutorfee_print_pdf' : 'invoice/detail/otherfee_print';

            $content = view($view, $invoice_data);
            $content_pdf = view($view_pdf, $invoice_data);

            if ($act == 'print') {
                // if ($invoice->printed_count >= config('constant.invoice_print_max_attemp')) {
                //     return response()->json(['code' => 0, 'message' => 'Hoá đơn số ' . $invoice->invoice_number . ' đã được in ' . $invoice->printed_count . ' lần!'], 500);
                // }

                if ($invoice->invoice_status == 0) {
                    $invoice->invoice_status = 1;
                } else if ($invoice->invoice_status == 1) {
                    $invoice->invoice_status = 2;
                }
                $invoice->printed_count++;
                $invoice->last_printed_time = $last_printed_time;
            }

            $invoice->invoice_content = $content_pdf;
            $invoice->save();

            if ($act == 'print') {
                //$this->send_invoice($invoice->id);
            }

            return $content;

        } catch (Exception $e) {
            Log::debug("PRINT ERROR: ". $e->getMessage());
        }
    }

    public function send_invoice($id)
    {
        $payment_methods = config('constant.payment_method');

        $invoice = Invoice::findOrfail($id);
        $student = Student::findOrfail($invoice->student_id);
        if ($student->parent_id == null) {
            return false;
        }

        $parent = Parents::findOrfail($student->parent_id);
        $to = $parent->email;

        if ($to == null) {
            $to = 'dungnv02@gmail.com';
        }

        $invoice->payment_method = $payment_methods[$invoice->payment_method];

        Mail::to($to)->send(new InvoicePrinted($invoice));
    }

    public function calc_prepaid_tutor_fee($student_id, $class_id)
    {
        $data = Invoice::get_last_tutor_duration($student_id, $class_id);
        return response()->json(['code' => 0, 'data' => $data], 200);
    }

    public function mark_delete_invoice(Request $request)
    {
        $input = $request->all();
        $invoice_id = $input['id'];
        $invoice = Invoice::findOrfail($invoice_id);
        $invoice->invoice_status = 4;
        $invoice->save();
        return response()->json(['code' => 0, 'data' => ['message' => 'OK']], 200);
    }

    public function approve_invoice(Request $request)
    {
        $input = $request->all();
        $invoice_id = $input['id'];
        $invoice = Invoice::findOrfail($invoice_id);
        $invoice->accountant_approved_date = date('Y-m-d H:i:s');
        $invoice->invoice_status = 3;
        $invoice->save();
        return response()->json(['code' => 0, 'data' => ['message' => 'OK']], 200);
    }

    public function clean_export($file)
    {
        $path = public_path('/storage/' . $file);
        if (empty($file) || !file_exists($path)) {
            return response()->json(['code' => 1, 'data' => ['message' => 'file not found!']], 500);
        } else {
            unlink($path);
            return response()->json(['code' => 0, 'data' => ['message' => 'file is removed!']], 200);
        }
    }

    public function export(Request $request)
    {
        try {
            $input = $request->all();
            $start_date = $input['start_date'];
            $end_date = $input['end_date'];
            $type = $input['type'];
            $status = $input['status'];
            $branch_id = $input['branch_id'];
            $class_id = $input['class_id'];

            $where = [
                'type' => $type,
                'branch_id' => $branch_id != 0 ? $branch_id : null,
                'invoice_status' => $status,
                'class_id' => $class_id,
            ];

            $ary_data = [];
            $date_range = '';

            if (!empty($start_date) && empty($end_date)) {
                $date_range = ' - from ' . date('d-m-Y', strtotime($start_date));
                $start_date = date('Y-m-d 00:00:00', strtotime($start_date));
                $where['created_at'] = $start_date;

            } else if (empty($start_date) && !empty($end_date)) {
                $date_range = ' - from ' . date('d-m-Y', strtotime($end_date));
                $end_date = date('Y-m-d 00:00:00', strtotime($end_date));
                $where['created_at'] = $end_date;
            } else if (!empty($start_date) && !empty($end_date)) {
                $date_range = ' - from ' . date('d-m-Y', strtotime($start_date)) . ' to ' . date('d-m-Y', strtotime($end_date));
                $start_date = date('Y-m-d 00:00:00', strtotime($start_date));
                $end_date = date('Y-m-d 23:59:59', strtotime($end_date));
                $where['created_at'] = [$start_date, $end_date];
            }

            $ary_title = [
                'No.',
                'Collecting Date',
                'No of Recept',
                'Code',
                'Name',
                'Parent Name',
                'Payment Method',
                'Course Name',
                'Discount',
                '',
                '',
                'Status',
                'Amount',
            ];

            $collection_data = Invoice::search_invoice($where);

            if (!is_null($collection_data)) {
                $payment_methods = config('constant.payment_method');
                $collection_data->map(function ($obj, $key) use (&$ary_data, $payment_methods) {
                    $ary_data[$key]['no'] = $key + 1;
                    $ary_tmp = (array) $obj;
                    foreach ($ary_tmp as $field => $value) {
                        if (in_array($field, ['id'])) {
                            continue;
                        }
                        if ($field == 'payment_method') {
                            $ary_data[$key][$field] = $payment_methods[$value];
                        } else if ($field == 'invoice_status') {
                            $ary_data[$key][$field] = config('constant.invoice_status')[$value];
                        } else {
                            $ary_data[$key][$field] = $value;
                        }
                    }

                    $ary_data[$key]['discount_type'] = !empty($ary_data[$key]['discount']) ? ($ary_data[$key]['discount_type'] == 'p' ? 'Percent' : 'Cash') : '';
                    $ary_data[$key] = (object)$ary_data[$key];
                });
            }

            $file_name = 'Invoice_export_' . date('YmdHis') . '.xlsx';

            $branch = !empty($input['branch_id']) ? Branch::findOrfail($branch_id) : null;

            $branch_name = !is_null($branch) ? $branch->branch_code : 'All center';

            $export_collection = collect($ary_data);

            $export = new InvoiceExport($export_collection, $branch_name, $date_range);
            $excel = Excel::store($export, $file_name);

            if ($excel) {
                copy(storage_path('app/').$file_name, public_path('/storage/' . $file_name));
                unlink(storage_path('app/').$file_name);
                return response()->json(['code' => 0, 'data' => ['excel' => $file_name ]], 200);
            }
            else {
                return response()->json(['code' => 0, 'data' => ['result' => false, 'message' => 'Export fail!']], 500);
            }

        } catch (Exception $e) {

            Log::debug('Export error:'. var_export($e->getTraceAsString()));

            return response()->json(['code' => 0, 'data' => ['result' => false, 'message' => 'Export fail!'. "\n". $e->getMessage()]], 500);
        }
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
