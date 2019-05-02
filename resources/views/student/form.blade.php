@extends('layouts.master')
@section('title')
	Hồ sơ chi tiết của học sinh
@endsection
@section('breadcrumb')
	<li class="active">Danh sách học sinh</li>
@endsection
@section('content')
<div class="row">
    <div class="group-tabs col-sm-11" style="padding-right:0">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="student_detail">
            <li class="active"><a data-tab="profile">Học sinh</a></li>
            <li><a data-tab="parents">Phụ huynh</a></li>
            <li><a data-tab="attendance">Lịch sử đi học</a></li>
            <li><a data-tab="exam_results">Kết quả thi</a></li>
            <li><a data-tab="teacher_reports">Báo cáo của giáo viên</a></li>
            <li><a data-tab="payment_histories">Lịch sử học phí</a></li>
        </ul>
    </div>
    <div class="col-sm-1" style="height:40px;text-align:right;padding-left:0"><button style="height:42px" type="button" role="button" class="btn btn-lg btn-success" id="btnSaveAll">Lưu</button></div>
</div>

<div class="row">
    <div class=" col-sm-12">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="profile">
                @include('student.detail.profile')
            </div>
            <div role="tabpanel" class="tab-pane" id="attendance">
                @include('student.detail.attendance')
            </div>
            <div role="tabpanel" class="tab-pane" id="parents">
                @include('student.detail.parent')
            </div>
            <div role="tabpanel" class="tab-pane" id="exam_results">
                @include('student.detail.exam')
            </div>
            <div role="tabpanel" class="tab-pane" id="teacher_reports">
                @include('student.detail.report')
            </div>
            <div role="tabpanel" class="tab-pane" id="payment_histories">
                @include('student.detail.payment')
            </div>
        </div>
    </div>
</div>
@endsection