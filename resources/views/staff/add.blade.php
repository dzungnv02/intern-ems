 @extends('layouts.master')
 @section('header')
 {{-- <link rel="stylesheet" href="{{asset('css/staff.css')}}"> --}}
 {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> --}}
 @endsection
 @section('title')
 Thêm mới nhân viên
 @endsection
 @section('content')
 <form action="" method="post" id="frmStaff">
 	<div class="form-group row">
 		<label for="inputEmail" class="col-sm-2 col-form-label">Email <i class="text-danger">*</i></label>
 		<div class="col-sm-8">
 			<input type="email" class="form-control" id="inputEmail" placeholder="Email">
 			<span id="errorEmail" class="bell errorEmail hidden">Email không đúng định dạng!</span>
 		</div>
 	</div>
 	<div class="form-group row">
 		<label for="inputPassword3" class="col-sm-2 col-form-label">Mật khẩu <i class="text-danger">*</i></label>
 		<div class="col-sm-8">
 			<input type="password" class="form-control" id="inputPassword" placeholder="Mật khẩu">
 			<span id="errorPassword" class="bell errorPassword hidden">Mật khẩu ít nhất 8 kí tự!</span>
 		</div>
 	</div>
 	<div class="form-group row">
 		<label for="inputPassword3" class="col-sm-2 col-form-label">Nhập lại mật khẩu <i class="text-danger">*</i></label>
 		<div class="col-sm-8">
 			<input type="password" class="form-control" id="inputPassword_1" placeholder="Nhập lại mật khẩu">
 			<span id="errorPassword_1" class="bell errorPassword_1 hidden">Mật khẩu phải giống nhau!</span>
 		</div>
 	</div>
 	<div class="form-group row">
 		<label for="inputPassword3" class="col-sm-2 col-form-label">Tên nhân viên <i class="text-danger">*</i></label>
 		<div class="col-sm-8">
 			<input type="text" class="form-control" id="inputName" placeholder="Tên nhân viên ">
 			<span id="errorName" class="bell errorName hidden">Tên nhân viên không được bỏ trống!</span>
 		</div>
	 </div>
	 <div class="form-group row">
		<label for="inputPhone" class="col-sm-2 col-form-label">Điện thoại <i class="text-danger">*</i></label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="inputPhone" placeholder="Điện thoại ">
			<span id="errorPhone" class="bell errorPhone hidden">Số điện thoại không đúng định dạng!</span>
		</div>
	</div>
	 <div class="form-group row">
		<label for="inputPassword3" class="col-sm-2 col-form-label">Chi nhánh <i class="text-danger">*</i></label>
		<div class="col-sm-8">
			<select id="branch_id">
				<option value="">[Chọn chi nhánh]</option>
			</select>
		</div>
	</div>
 	<div class="form-group row">
 		<label for="inputPassword3" class="col-sm-2 col-form-label">Giới tính</label>
 		<div class="col-sm-8">
 			<select name="gender" id="inputGender">
 				<option value="1">Nam</option>
 				<option value="0">Nữ</option>
 				<option value="2" selected>Khác</option>
 			</select>
 		</div>
 	</div>
 	<div class="form-group row">
 		<label for="inputPassword3" class="col-sm-2 col-form-label">Ngày Sinh</label>
 		<div class="col-sm-8">
 			<input type="date" class="form-control" id="inputBirthDate">
 		</div>
 	</div>
 	<div class="form-group row">
 		<label for="inputAddress" class="col-sm-2 col-form-label">Địa chỉ</label>
 		<div class="col-sm-8">
 			<input type="text" class="form-control" id="inputAddress" placeholder="Địa chỉ">
 		</div>
 	</div>
 	<div class="form-group row">
 		<div class="col-sm-2">
 			<button class="btn btn-primary" id="addStaff" type="button">Thêm nhân viên</button>
 		</div>
 		<div class="col-sm-2">
 			<button type="reset" class="btn btn-warning" id="resetForm">Làm mới</button>
 		</div>
 	</div>
 </form>
 @endsection
 @section('footer')
 {{-- <script src="{{asset('js/staff.js')}}"> --}}
 </script>
 {{-- <script src="{{asset('js/staff/add.js')}}"> --}}
 {{-- </script> --}}
 @endsection