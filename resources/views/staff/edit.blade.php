 @extends('layouts.master')
 @section('header')
 {{-- <link rel="stylesheet" href="{{asset('css/staff.css')}}"> --}}
 @endsection
 @section('title')
 Sửa nhân viên
 @endsection
 @section('content')
 <form action="" method="post" id="abs">
 	<div class="form-group row">
 		<label for="inputEmail3" class="col-sm-2 col-form-label">Email *</label>
 		<div class="col-sm-8">
 			<input type="email" class="form-control" id="inputEmail3" placeholder="Email">
 			<span id="errorEmail" class="bell errorEmail">Email không đúng định dạng!</span>
 		</div>
 	</div>
 	<div class="form-group row">
 		<label for="inputPassword3" class="col-sm-2 col-form-label">Mật khẩu *</label>
 		<div class="col-sm-8">
 			<input type="password" class="form-control" id="inputPassword" placeholder="Mật khẩu">
 			<span id="errorPassword" class="bell errorPassword">Mật khẩu ít nhất 8 kí tự!</span>
 		</div>
 	</div>
 	<div class="form-group row">
 		<label for="inputPassword3" class="col-sm-2 col-form-label">Nhập lại mật khẩu *</label>
 		<div class="col-sm-8">
 			<input type="password" class="form-control" id="inputPassword_1" placeholder="Nhập lại mật khẩu">
 			<span id="errorPassword_1" class="bell errorPassword_1">Mật khẩu phải giống nhau!</span>
 		</div>
 	</div>
 	<div class="form-group row">
 		<label for="inputPassword3" class="col-sm-2 col-form-label">Tên nhân viên *</label>
 		<div class="col-sm-8">
 			<input type="text" class="form-control" id="inputName" placeholder="Tên nhân viên ">
 			<span id="errorName" class="bell errorName">Tên nhân viên không được bỏ trống!</span>
 		</div>
 	</div>
 	<div class="form-group row">
 		<label for="inputPassword3" class="col-sm-2 col-form-label">Giới tính *</label>
 		<div class="col-sm-8">
 			<select name="gender" id="inputGender">
 				<option value="1">Nam</option>
 				<option value="0">Nữ</option>
 				<option value="2">Khác</option>
 			</select>
 		</div>
 	</div>
 	<div class="form-group row">
 		<label for="inputPassword3" class="col-sm-2 col-form-label">Ngay Sinh *</label>
 		<div class="col-sm-8">
 			<input type="date" class="form-control" id="inputBirthDate">
 		</div>
 	</div>
 	<div class="form-group row">
 		<label for="inputPassword3" class="col-sm-2 col-form-label">Địa chỉ *</label>
 		<div class="col-sm-8">
 			<input type="text" class="form-control" id="inputAddress" placeholder="Địa chỉ">
 		</div>
 	</div>
 	<div class="form-group row">
 		<label for="inputPassword3" class="col-sm-2 col-form-label">Điện thoại  *</label>
 		<div class="col-sm-8">
 			<input type="text" class="form-control" id="inputPhone" placeholder="Điện thoại ">
 			<span id="errorPhone" class="bell errorPhone">Số điện thoại không đúng định dạng!</span>
 		</div>
 	</div>
 	<div class="form-group row">
 		<label for="inputPassword3" class="col-sm-2 col-form-label">Hình ảnh *</label>
 		<div class="col-sm-8">
 			<input type="file" class="form-control" id="inputFile">
 			<span id="errorImg" class="bell errorImg">Ảnh không đúng định dạng!</span>
 			<img src="" alt="" id="showImg" style="">
 		</div>
 	</div>
 	<div class="form-group row">
 		<div class="col-sm-2">
 			<button type="button" class="btn btn-primary" id="editStaff_1">Sửa nhân viên</button>
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
 @endsection