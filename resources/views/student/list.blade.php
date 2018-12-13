@extends('layouts.master')
@section('title')
	Danh sách học viên
@endsection
@section('breadcrumb')
	<li class="active">danh sách học viên</li>
@endsection
@section('content')
	<div><button class="btn btn-info add-student">Thêm học sinh</button></div><br>
	<div class="card-body table-reponsive">
		<table class="table table-bordered table-striped" id="list-student">
			<thead>
				<tr>
					<th style="width:5%">#</th>
					<th style="width:10%">Mã học sinh</th>
					<th style="width:25%">Tên học sinh</th>
					<th style="width:15%">Lớp đang học</th>
					<th style="width:20%">Phụ huynh</th>
					<th style="width:10%">Năm sinh</th>
					<th style="width:10%"></th>
				</tr>
			</thead>
		</table>
	</div></br>

	<div class="modal fade" id="student-form-modal" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document" style="width:800px">
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h2 class="modal-title" id="modal-teacher-form-title">Modal title</h2>
			</div>
			<div class="modal-body">
				<form id="frmStudent">
					<input type="text" class="form-control hidden" id="id">
					<input type="text" class="form-control hidden" id="crm_id">
					<div class="box-body">
						<div class="row">
							<div class="form-group col-sm-6">
								<label for="name" class="col-form-label">Họ tên:<i style="color:red">*</i></label>
								<input type="text" class="form-control" id="name" placeholder="Họ tên học sinh">
							</div>
							<div class="form-group col-sm-6">
								<label for="gender" class="col-form-label">Giới tính:</label>
								<select class="form-control" id="gender">
									<option value="1">Nam</i></option>
									<option value="0">Nữ</option>
									<option value="2">Khác</option>
								</select>
							</div>
						</div>

						<div class="row">
							<div class="form-group col-sm-6">
								<label for="email" class="col-form-label">Email:</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									<input type="email" class="form-control" id="email" placeholder="Email">
								</div>
							</div>
							<div class="form-group col-sm-6">
								<label for="mobile" class="col-form-label">Số điện thoại:</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-phone"></i></span>
									<input type="text" class="form-control" id="mobile" placeholder="Số điện thoại">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-sm-6">
								<label for="birthday" class="col-form-label">Ngày sinh:</label>
								<div class="input-group date">
									<span class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</span>
									<input type="date" class="form-control pull-right" id="birthday" placeholder="Ngày sinh">
								</div>
							</div>
							<div class="form-group col-sm-6">
								<label for="mobile" class="col-form-label">Năm sinh:</label>
								<input type="text" class="form-control" id="birthyear" placeholder="Năm sinh">
							</div>
						</div>
						<div class="row">
							<div class="form-group col-sm-12">
									<label for="address">Địa chỉ liên lạc:</label>
									<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-location-arrow"></i>
										</span>
										<input type="text" class="form-control" id="address" placeholder="Địa chỉ">
									</div>
							</div>
						</div>
						<hr style="border-top:1px solid #ddd" />
						<div class="row">
							<div class="form-group col-sm-12">
								<label>Phụ huynh:</label>
								<div class="row">
									<div class="col-sm-6" style="padding-right:0">
										<div class="input-group">
											<span class="input-group-addon">
												<i class="fa fa-search"></i>
											</span>
											<input type="text" class="form-control" id="parent-search" placeholder="tìm theo email hoặc số điện thoại">
										</div>
									</div>
									<div class="col-sm-6" style="padding-left:0;text-align:left">
										<button type="button" style="height:34px" class="btn btn-info"><i class="fa fa-search"></i></button>&nbsp;
										<button type="button" style="height:34px" class="btn btn-success add-parent"><i class="fa fa-plus"></i></button>
									</div>
								</div>
							</div>
						</div>
						<div class="row" id="parent-info" style="display:none">
							<div class="col-sm-12">
								<div style="display:block;height:100%;width:100%;border:1px solid #eee;padding:15px 5px 15px 5px">
									<input type="hidden" class="form-control" name="parent-id" id="parent-id">
									<div class="row">
										<div class="orm-group col-sm-4">
											<label for="parent-name" class="col-form-label">Họ tên phụ huynh:<i style="color:red">*</i></label>
											<input type="text" class="form-control" name="parent-fullname" id="parent-fullname" placeholder="Họ tên phụ huynh">
										</div>
										<div class="orm-group col-sm-4">
											<label for="parent-email" class="col-form-label">Email:</label>
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
												<input type="email" class="form-control" id="parent-email" name="parent-email" placeholder="Email của phụ huynh">
											</div>
										</div>
										<div class="orm-group col-sm-4">
											<label for="parent-phone" class="col-form-label">Số điện thoại:<i style="color:red">*</i></label>
											<div class="input-group">
													<span class="input-group-addon"><i class="fa fa-phone"></i></span>
												<input type="text" class="form-control" id="parent-phone" name="parent-phone" placeholder="Số điện thoại của phụ huynh">
											</div>
										</div>
									</div>
									<div class="row" style="padding-top:10px">
										<div class="orm-group col-sm-4">
											<label for="gender" class="col-form-label">Vai trò phụ huynh:</label>
											<input type="text" class="form-control" name="parent-role" id="parent-role" placeholder="Bố, mẹ, ông, bà ...">
										</div>
										<div class="orm-group col-sm-8">
											<label for="parent-facebook" class="col-form-label">Facebook:</label>
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-facebook-official"></i></span>
												<input type="email" class="form-control" id="parent-facebook" name="parent-facebook" placeholder="Đường link của tài khoản Facebook">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-default" data-dismiss="modal">Đóng</button>
				<button type="button" class="btn btn-primary" id="btnSave" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Đang lưu...">Lưu</button>
			</div>
			</div>
		</div>
	</div>


{{--  Confirm dialog  --}}
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="confirm-delete">
    <div class="modal-dialog modal-sm modal-dialog-centered" style="width:400px">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h5 class="modal-title" id="myModalLabel">Bạn có muốn xoá lớp %s không?</h5>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" id="modal-btn-yes">Có</button>
            <button type="button" class="btn btn-primary" id="modal-btn-no">Không</button>
        </div>
        </div>
    </div>
</div>
{{--  END - Confirm dialog  --}}
@endsection