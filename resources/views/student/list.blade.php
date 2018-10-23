@extends('layouts.master')
@section('title')
	Danh sách học viên
@endsection
@section('breadcrumb')
	<li class="active">danh sách học viên</li>
@endsection
@section('content')
	<div><button class="btn btn-info add-student">Thêm học viên</button></div><br>
	<div class="card-body table-reponsive">
		<table class="table table-bordered table-striped" id="list-student">
			<thead>
				<tr>
					<th>STT</th>
					<th data-field="name">Tên học viên</th>
					<th data-field="email">Email</th>
					<th data-field="address">Địa chỉ</th>
					<th data-field="mobile">Số điện thoại</th>
					<th data-field="birthday">Ngày sinh</th>
					<th data-field="gender">Giới tính</th>
					<th>Action</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>STT</th>
					<th data-field="name">Tên học viên</th>
					<th data-field="email">Email</th>
					<th data-field="address">Địa chỉ</th>
					<th data-field="mobile">Số điện thoại</th>
					<th data-field="birthday">Ngày sinh</th>
					<th data-field="gender">Giới tính</th>
					<th>Action</th>
				</tr>
			</tfoot>
		</table>
	</div></br>

	<div class="modal fade" id="add-student">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Thêm học viên</h4>
				</div>
				<div class="modal-body">
					<form id="form-add" method="POST" role="form">
						@csrf
						<div class="form-group">
							<label for="">Tên học viên</label>
							<input name="name" type="text" class="form-control" id="name">
						</div>
						<div class="form-group">
							<label for="">Mã học viên</label>
							<input name="student_code" type="text" class="form-control" id="student_code">
							<p style="color: red" class="unique_student_code"></p>
						</div>
						<div class="form-group">
							<label for="">Email</label>
							<input name="email" type="text" class="form-control" id="email">
							<p style="color: red" class="unique_email"></p>
						</div>
						<div class="form-group">
							<label for="">Địa chỉ</label>
							<input name="address" type="text" class="form-control" id="address">
						</div>
						<div class="form-group">
							<label for="">Số điện thoại</label>
							<input name="mobile" type="number" class="form-control" id="mobile">
						</div>
						<div class="form-group">
							<label for="">Ngày sinh</label>
							<input name="birthday" type="text" readonly class="form-control" id="birthday">
						</div>
						<div class="form-group">
							<label for="">Giới tính</label>
							<select class="form-control" name="gender" id="gender">
								<option value="0">Nam</option>
								<option value="1">Nữ</option>
							</select>
						</div>
						
						<button id="store-student" type="submit" class="btn btn-primary">Save</button>
					</form>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="edit-student">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Sửa kì thi</h4>
				</div>
				<div class="modal-body">
					<form action="" method="POST" role="form" id="form-edit">
						@csrf
						<div class="form-group">
							<label for="">Tên học viên</label>
							<input name="name" type="text" class="form-control" id="edit_name">
						</div>
						<div class="form-group">
							<label for="">Mã học viên</label>
							<input name="student_code" type="text" class="form-control" id="edit_student_code">
							<p style="color: red" class="unique_student_code"></p>
						</div>
						<div class="form-group">
							<label for="">Email</label>
							<input name="email" type="text" class="form-control" id="edit_email">
							<p style="color: red" class="unique_email"></p>
						</div>
						<div class="form-group">
							<label for="">Địa chỉ</label>
							<input name="address" type="text" class="form-control" id="edit_address">
						</div>
						<div class="form-group">
							<label for="">Số điện thoại</label>
							<input name="mobile" type="number" class="form-control" id="edit_mobile">
						</div>
						<div class="form-group">
							<label for="">Ngày sinh</label>
							<input name="birthday" type="text" readonly class="form-control" id="edit_birthday">
						</div>
						<div class="form-group">
							<label for="">Giới tính</label>
							<select class="form-control" name="gender" id="edit_gender">
								<option value="0">Nam</option>
								<option value="1">Nữ</option>
							</select>
						</div>
						
						<button id="update-student" data-id="" type="submit" class="btn btn-primary">Update</button>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

    <div class="modal fade" id="list-class">
    	<div class="modal-dialog">
    		<div class="modal-content">
    			<div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    				<h4 class="modal-title">Danh sách lớp đang tuyển sinh</h4>
    			</div>
    			<div class="modal-body">
    				<input type="hidden" value="" id="get_student_id">
    				<table class="table table-bordered table-striped" id="table-enroll-class">
						<thead>
							<tr>
								<th>STT</th>
								<th>Mã lớp</th>
								<th>Tên lớp</th>
								<th>Tên khóa học</th>
								<th>Sĩ số</th>
								<th>Action</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>STT</th>
								<th>Mã lớp</th>
								<th>Tên lớp</th>
								<th>Tên khóa học</th>
								<th>Sĩ số</th>
								<th>Action</th>
							</tr>
						</tfoot>
					</table>
    			</div>
    			<div class="modal-footer">
    				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    			</div>
    		</div>
    	</div>
    </div>
@endsection