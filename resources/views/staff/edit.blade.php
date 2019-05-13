 @extends('layouts.master')
 @section('header')
 @endsection
 @section('title')
 Sửa nhân viên
 @endsection
 @section('content')
 <form action="" method="post" id="frmStaff">
	<div class="form-group row">
 		<label for="inputEmail" class="col-sm-2 col-form-label">Email *</label>
 		<div class="col-sm-8">
 			<input type="email" class="form-control" id="inputEmail" placeholder="Email">
 			<span id="errorEmail" class="bell errorEmail">Email không đúng định dạng!</span>
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
 		<label for="inputGender" class="col-sm-2 col-form-label">Giới tính *</label>
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
 </script>
 @endsection