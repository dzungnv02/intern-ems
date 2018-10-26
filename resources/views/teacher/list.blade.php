	@extends('layouts.master')
	@section('page-title')
	Danh sách giáo viên
	@endsection
	@section('title')
	Danh sách giáo viên<span></span>
	@endsection
	@section('breadcrumb')
	<li class="active">Danh sách giáo viên</li>
	@endsection
	@section('content')
	<button id="btnOpenModalTeacher" class="btn btn-info">Thêm mới</button><br><br>
	<div class="card-body table-reponsive">
		<table class="table table-bordered table-striped" id="teacher-list">
			<thead>
				<tr>
					<th>STT</th>
					<th>Họ tên</th>
					<th>Email</th>
					<th>Số ĐT</th>
					<th>Địa chỉ</th>
					<th>Quốc tịch</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>	
	</div>
	@endsection
	@section('footer')

	<div class="modal fade" id="modal-teacher-form" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document" style="width:800px">
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h2 class="modal-title" id="modal-teacher-form-title">Modal title</h2>
			</div>
			<div class="modal-body">
				<form id="frmTeacher">
					<input type="text" class="form-control hidden" id="id">
					<input type="text" class="form-control hidden" id="crm_id">

					<div class="box-body">
						<div class="row">
							<div class="form-group col-sm-6">
								<label for="name" class="col-form-label">Họ tên:<i style="color:red">*</i></label>
								<input type="text" class="form-control" id="name" placeholder="Họ tên giáo viên">
							</div>
							<div class="form-group col-sm-6">
								<label for="nationality" class="col-form-label">Quốc tịch:<i style="color:red">*</i></label>
								<select class="form-control select2" name="nationality" id="nationality" style="width: 100%;"></select>
							</div>
						</div>

						<div class="row">
							<div class="form-group col-sm-6">
								<label for="email" class="col-form-label">Email:<i style="color:red">*</i></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									<input type="email" class="form-control" id="email" placeholder="Email">
								</div>
							</div>
							<div class="form-group col-sm-6">
								<label for="mobile" class="col-form-label">Số điện thoại:<i style="color:red">*</i></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-phone"></i></span>
								<input type="text" class="form-control" id="mobile" placeholder="Số điện thoại">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="form-group col-sm-6">
								<label for="birthdate" class="col-form-label">Ngày sinh:</label>
								<div class="input-group date">
									<span class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</span>
									<input type="text" class="form-control pull-right" id="birthdate" placeholder="Ngày sinh">
								</div>
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
						<div class="row">
							<div class="form-group col-sm-6">
									<label for="experience">Số năm kinh nghiệm:</label>
									<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-trophy"></i>
										</span>
										<input type="text" class="form-control" id="experience" placeholder="Kinh nghiệm tính theo năm">
									</div>
							</div>
							<div class="form-group col-sm-6">
									<label for="certificate">Chứng chỉ:</label>
									<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-certificate"></i>
										</span>
										<input type="text" class="form-control" id="certificate" placeholder="Các chứng chỉ, bằng cấp">
									</div>
							</div>
						</div>

						<div class="row">
							<div class="form-group col-sm-12">
									<label for="description">Thông tin thêm:</label>
									<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-info"></i>
										</span>
										<textarea id="description" class="form-control" rows="3" placeholder="Thông tin thêm"></textarea>
									</div>
							</div>
						</div>

					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
				<button type="button" class="btn btn-primary" id="btnSave">Lưu</button>
			</div>
			</div>
		</div>
		</div>
	<script>
		var base_url = "{{ url('/') }}/";
		$('SELECT.select2[id="nationality"]').select2();
	</script>
	@endsection