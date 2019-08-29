 @extends('layouts.master')
 @section('header')
 <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
 @endsection
 @section('title')
 Hoá đơn thu tiền
 @endsection
 @section('content')
 <div class="row">
    <div class="group-tabs col-sm-10" style="padding-right:0">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="invoice_tabs">
            <li class="nav-link active"><a data-tab="invoicelist-tab">Danh sách hoá đơn</a></li>
            <li class="nav-link disabled"><a data-tab="tutorfee-tab">Học phí</a></li>
            <li class="nav-link disabled"><a data-tab="otherfee-tab">Các loại thu khác</a></li>
        </ul>
    </div>
    <div class="col-sm-2" style="height:40px;text-align:right;padding-left:0">
        <button style="height:42px" type="button" role="button" class="btn btn-lg btn-success" id="btnCreateInvoice">Tạo phiếu thu</button>
        <button style="height:42px" type="button" role="button" class="btn btn-lg btn-success" id="btnPrintInvoice">In phiếu thu</button>
        <button style="height:42px" type="button" role="button" class="btn btn-lg btn-info" id="btnExportInvoice" data-toggle="modal" data-target="#invoice-export-modal">Kết xuất</button>
        <button style="height:42px" type="button" role="button" class="btn btn-lg btn-info" id="btnSaveInvoice">Lưu</button>
    </div>
</div>

<div class="row">
    <div class=" col-sm-12">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="invoicelist-tab">
                @include('invoice.detail.list')
            </div>
            <div role="tabpanel" class="tab-pane" id="tutorfee-tab">
                @include('invoice.detail.tutorfee')
            </div>
            <div role="tabpanel" class="tab-pane" id="otherfee-tab">
                @include('invoice.detail.otherfee')
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="view-invoice" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:1100px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

 @endsection
 @section('footer')
 @endsection