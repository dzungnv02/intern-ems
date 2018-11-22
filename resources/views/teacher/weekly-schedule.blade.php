@extends('layouts.master')
@section('page-title')
Lịch hàng tuần của giáo viên
@endsection
@section('title')
Lịch hàng tuần của giáo viên<span></span>
@endsection
@section('breadcrumb')
<li class="active">Lịch hàng tuần của giáo viên</li>
@endsection
@section('content')
<div class="row" style="magin:15px">
    <form id="frmReport">
        <div class="form-group col-sm-7">
        </div>
        <div class="form-group col-sm-2" style="text-align:right">
            <input type="date" class="form-control" id="start" placeholder="Chọn ngày bắt đầu">
        </div>
        <div class="form-group col-sm-2" style="text-align:right">
                <input type="date" class="form-control" id="end" placeholder="Chọn ngày kết thúc">
        </div>
        <div class="form-group col-sm-1" style="text-align:right">
                <button type="button" id="btnRefresh" class="btn btn-info"><i class="fa fa-refresh"></i></button>
        </div>
    </form>
</div>
<div class="row">
        <div class="col-sm-12">&nbsp;</div>
</div>
<div class="row" style="magin:15px">
    <div class="col-sm-12">
        <div class="card-body table-reponsive">
            <table class="table table-bordered table-striped" id="teacher-weekly-schedule">
                <thead>
                    <tr id="weekdays">
                        <th style="text-align:center"></th>
                        <th style="text-align:center">Thứ 2</th>
                        <th style="text-align:center">Thứ 3</th>
                        <th style="text-align:center">Thứ 4</th>
                        <th style="text-align:center">Thứ 5</th>
                        <th style="text-align:center">Thứ 6</th>
                        <th style="text-align:center">Thứ 7</th>
                        <th style="text-align:center;color:red">CN</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <div class="row" style="margin-bottom:15px">
                <div class="col-sm-5"></div>
                <div class="col-sm-7">
                    <div class="paginate" style="white-space: nowrap;text-align: right;height:43px"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="confirm-delete">
    <div class="modal-dialog modal-sm"  style="width:400px">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h5 class="modal-title" id="myModalLabel">Bạn có muốn xoá giáo viên %s không?</h5>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" id="modal-btn-yes">Có</button>
            <button type="button" class="btn btn-primary" id="modal-btn-no">Không</button>
        </div>
        </div>
    </div>
</div>

<script>
    var base_url = "{{ url('/') }}/";
</script>
@endsection