@extends('layouts.master')
@section('page-title')
	Danh sách kì thi
@endsection
@section('title')
	Danh sách kì thi
@endsection
@section('breadcrumb')
	<li class="active">danh sách kì thi</li>
@endsection
@section('content')
	
	{{-- button thêm kì thi --}}
	<div><button class="button-add btn btn-info add-exam">Thêm kì thi</button></div><br>

	{{-- bảng danh sách kì thi --}}
	<div class="card-body table-reponsive">
		<table class="table table-bordered table-striped" id="list-exam">
			<thead>
				<tr>
					<th>STT</th>
					<th>Tên kì thi</th>
					<th>Tên lớp</th>
					<th>Thời gian thi</th>
					<th>Thời lượng</th>
					<th>Ghi chú</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<tfoot>
				<tr>
					<th>STT</th>
					<th>Tên kì thi</th>
					<th>Tên lớp</th>
					<th>Thời gian thi</th>
					<th>Thời lượng</th>
					<th>Ghi chú</th>
					<th>Action</th>
				</tr>
				</tfoot>
		</table>
	</div></br>

	{{-- modal thêm kì thi --}}
	<div class="modal fade" id="model-add">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Thêm kì thi</h4>
				</div>
				<div class="modal-body">
					<form id="form-add-exam" action="" method="POST" role="form">
						<div class="form-group">
							<label for="">Tên kì thi</label>
							<input name="name"  type="text" class="form-control" id='name'>
						</div>
						<div class="form-group">
							<label for="">Chọn lớp</label>
							<select class="form-control" name="" id="name-class">
							</select>
						</div>
						<div class="form-group">
							<label for="">Thời gian thi</label>
							<input name="start_day"  type="text" readonly  class="form-control" id='start_day'>
						</div>
						<div class="form-group">
							<label for="">Thời lượng</label>
							<input name="duration"  type="text" class="form-control" id='duration'>
						</div>
						<div class="form-group">
							<label for="">Ghi chú</label>
							<input name="note" type="text" class="form-control" id="note">
						</div>
						
						    <button type="submit" id="add-exam" class="btn btn-primary">Thêm</button>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	{{-- modal edit kì thi --}}
	<div class="modal fade" id="edit-exam">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Sửa kì thi</h4>
					<input type="hidden" id="update-exam-hd">

				</div>
				<div class="modal-body">
					<form id="form-edit-exam" action="" method="POST" role="form">
						<div class="form-group">
							<label for="">Tên kì thi</label>
							<input name="name" type="text" class="form-control" id="ename">
						</div>
						<div class="form-group">
							<label for="">Chọn lớp</label>
							<select class="form-control" name="class" id="ename_class">
							</select>
						</div>
						<div class="form-group">
							<label for="">Thời gian thi</label>
							<input name="start_day" type="text" readonly class="form-control" id="estart_day">
						</div>

						<div class="form-group">
							<label for="">Thời gian</label>
							<input name="duration" type="text" class="form-control" id="eduration">
						</div>
						<div class="form-group">
							<label for="">Ghi chú</label>
							<input name="note" type="text" class="form-control" id="enote">
						</div>
						
						<input id="update-exam" value="Update" type="submit" class="btn btn-primary">
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	{{-- modal thêm kì thi --}}
	<div class="modal fade" id="model-add-setPoint">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Thêm điểm</h4>
				</div>
           <div class="modal-body">
               <input type="hidden" name="" value="" id="get_class_id">
                <table class="table table-bordered table-striped" id="set-point">
			<thead>
				<tr>
							<th>STT</th>
							<th>Mã học viên</th>
							<th>Tên học viên</th>
							<th>Điểm</th>
				</tr>
				</thead>
				<tfoot>
				<tr>
							<th>STT</th>
							<th>Mã học viên</th>
							<th>Tên học viên</th>
							<th>Điểm</th>
				</tr>
				</tfoot>
                </table>
            </div>
	{{-- button lưu điểm --}}
            <div class="modal-footer">
				<button id="setPoint" class="btn btn-primary">Lưu điểm</button>
                <button type="button" class="close-modal-add-student-class btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
        </div>
			</div>
		</div>
	</div>
	
		{{-- modal xem điểm thi --}}
	<div class="modal fade" id="model-get-Point">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Điểm kì thi</h4>
				</div>
           <div class="modal-body">
               <input type="hidden" name="" value="" id="get_class_id">
                <table class="table table-bordered table-striped" id="get-point">
			<thead>
				<tr>
					<th>Mã học viên</th>
					<th>Tên học viên</th>
					<th>Điểm</th>
					<th>Action</th>
							
				</tr>
				</thead>
				<tfoot>
				<tr>
					<th>Mã học viên</th>
					<th>Tên học viên</th>
					<th>Điểm</th>
					<th>Action</th>
				</tr>
				</tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class=" btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
        </div>
			</div>
		</div>
	</div>
	
@endsection