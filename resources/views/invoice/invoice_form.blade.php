 @extends('layouts.master')
 @section('header')
 <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
 @endsection
 @section('title')
 Hoá đơn thu tiền
 @endsection
 @section('content')
 <div class="row">
    <div class="group-tabs col-sm-11" style="padding-right:0">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="invoice_tabs">
            <li class="active"><a data-tab="tutorfee-tab">Học phí</a></li>
            <li><a data-tab="otherfee-tab">Các loại thu khác</a></li>
        </ul>
    </div>
    <div class="col-sm-1" style="height:40px;text-align:right;padding-left:0"><button style="height:42px" type="button" role="button" class="btn btn-lg btn-success" id="btnSaveAll">Lưu</button></div>
</div>

<div class="row">
    <div class=" col-sm-12">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="tutorfee-tab">
                @include('invoice.detail.tutorfee')
            </div>
            <div role="tabpanel" class="tab-pane" id="otherfee-tab">
                @include('invoice.detail.otherfee')
            </div>
        </div>
    </div>
</div>

 @endsection
 @section('footer')
 @endsection