<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\Classes;
use App\Invoice;

class InvoicePController extends Controller
{
    /**
     * Create an Invoice
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('invoicep/invoice_form');
    }

    public function getInvoiceList(Request $request)
    {
        $invoices = Invoice::get_list_invoice();
        return response()->json(['code' => 0, 'data' => ['list' => $invoices]], 200);
    }
}
