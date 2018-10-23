@extends('layouts.master')
@section('header')
{{-- <link rel="stylesheet" href="{{ asset('css/teacher.css') }}"> --}}
@endsection
@section('title')
<h4 class="box-title">Chỉnh sửa thông tin giáo viên</h4>
@endsection
@section('breadcrumb')
{{--  <li>
	<a href="{{asset(''')}}"><i class="fa fa-dashboard"></i> Trang chủ</a>
</li>  --}}
<li>
	<a href="{{asset('teacher/list')}}"><i class="fa fa-list" aria-hidden="true"></i></i> 
		Chỉnh sửa thông tin giáo viên
	</a>
</li>
@endsection
@section('content')
<form class="add-teacher" id="formEdit">
	<div class="form-group">
		<label for="name">Tên:</label>
		<input type="text" class="form-control" id="name" placeholder="Tên" name="name">
	</div>
	<div class="form-group">
		<label for="name">Email:</label>
		<input type="email" class="form-control" id="email" placeholder="Email" name="email">
	</div>
	<div class="form-group">
		<label for="address">Địa chỉ:</label>
		<input type="text" class="form-control" id="address" placeholder="Địa chỉ" name="address">
	</div>
	<div class="form-group">
		<label for="mobile">Số điện thoại:</label>
		<input type="text" class="form-control" id="mobile" placeholder="Số điện thoại" name="mobile">
	</div>
	<div class="form-group">
		<label for="name">Ngày sinh:</label>
		<input type="date" class="form-control" id="birthday" placeholder="Ngày sinh" name="birthdate">
	</div>
	<div class="form-group">
		<label for="name">Giới tính:</label>
		<select class="form-control" id="gender" name="gender">
			<option>Nam</option>
			<option>Nữ</option>
			<option>Khác</option>
		</select>
	</div>
	<div class="form-group">
		<label for="name">Kinh nghiem</label>
		<input type="text" class="form-control" id="exp" placeholder="Kinh nghiệm tính theo năm" name="exp">
	</div>
	<div class="form-group">
		<label for="name">Bằng cấp</label>
		<input type="text" class="form-control" id="certifycate" placeholder="Bằng cấp" name="certifycate">
	</div>
	<div class="form-group">
		<label for="name">Thông tin thêm</label>
		<input type="text" class="form-control" id="err-description" placeholder="Thông tin thêm" name="descript">
		{{-- <span class="text-danger">Bằng cấp không được để trống !</span> --}}
	</div>
	<div class="center-block">
		<a href="#" class="btn btn-success" id="edit-teacher">Edit</a>
		<a href="{{asset('teacher/list')}}" class="btn btn-danger">Hủy</a>
	</div>

</form>


@endsection 
@section('footer')
{{-- <script src="{{ asset('js/teacher.js') }}"></script> --}}
<script>

</script>
@endsection

