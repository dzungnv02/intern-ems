@extends('layouts.master')
@section('header')
	<style>
/* The container */
.container1 {
    display: inline-block;
    position: relative;
    padding-left: 22px;
    margin-right: 9px;
    cursor: pointer;
    font-size: 17px;
}

/* Hide the browser's default radio button */
.container1 input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

/* Create a custom radio button */
.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 20px;
    width: 20px;
    background-color: #ddd;
    border-radius: 50%;
}

/* On mouse-over, add a grey background color */
.container1:hover input ~ .checkmark {
    background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.container1 input:checked ~ .checkmark {
    background-color: #2196F3;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the indicator (dot/circle) when checked */
.container1 input:checked ~ .checkmark:after {
    display: block;
}

/* Style the indicator (dot/circle) */
.container1 .checkmark:after {
 	top: 6px;
	left: 6px;
	width: 8px;
	height: 8px;
	border-radius: 50%;
	background: white;
}
</style>
@endsection
@section('pagetitle')
	Điểm danh cho lớp
@endsection
@section('title')
	Điểm danh cho lớp<span></span>
@endsection
@section('breadcrumb')
    <li><a href="{{ asset('class') }}">Danh sách lớp</a></li>
    <li><a href="{{ asset('timetable') }}">Thời khóa biểu</a></li>
    <li class="active">Điểm danh</li>
@endsection
@section('content')

	{{-- bảng điểm danh --}}
	<div class="card-body table-reponsive">
		<table class="table table-bordered table-striped" id="table-rollcall">
			<thead>
				<tr>
					<th>STT</th>
					<th data-field="student_code">Mã học viên</th>
					<th data-field="name">Tên học viên</th>
					<th data-field="status">Điểm danh</th>
					<th data-field="note">Ghi chú</th>
					<th>Action</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>STT</th>
					<th data-field="student_code">Mã học viên</th>
					<th data-field="name">Tên học viên</th>
					<th data-field="status">Điểm danh</th>
					<th data-field="note">Ghi chú</th>
					<th>Action</th>
				</tr>
			</tfoot>
		</table>
	</div></br>
	<div class="modal fade" id="enter-note">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Nhập ghi chú</h4>
                </div>
                <div class="modal-body">
                    <input id="rollID" type="hidden">
                    <form action="" method="POST" role="form">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" id="note1" type="text">
                        </div>
                        <button data-id="" type="submit" id="update-note" class="btn btn-primary">Update</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection