 {{-- ke thua file master --}}
 @extends('layouts.master')
 {{-- end --}}
 @section('header')
 {{-- <meta name="_token" content="{{ csrf_token() }}"> --}}
 {{-- <link rel="stylesheet" href="{{asset('css/staff.css')}}"> --}}
 @endsection
 @section('title')
 Danh sách nhân viên
 @endsection
 @section('content')
 <a href="{{asset('staff-add')}}" class="btn btn-primary _button">Thêm nhân viên </a>
 <table id="staff_list" class="table table-striped table-bordered" style="width:100%">
 	<thead>
 		<tr>
 			<th>Stt</th>
 			<th data-field="name">Tên</th>
 			<th data-field="email">Email</th>
 			<th data-field="gender">Giới tính</th>
 			<th data-field="image">Ảnh đại diện</th>
 			<th data-field="birth_date">Ngày sinh</th>
 			<th data-field="address">Địa chỉ</th>
 			<th data-field="phone_number">Điện thoại</th>
 			<th>Action</th>
 		</tr>
 	</thead>
 	<tfoot>
 		<tr>
 			<th>Stt</th>
 			<th data-field="name">Tên</th>
 			<th data-field="email">Email</th>
 			<th data-field="gender">Giới tính</th>
 			<th data-field="image">Ảnh đại diện</th>
 			<th data-field="birth_date">Ngày sinh</th>
 			<th data-field="address">Địa chỉ</th>
 			<th data-field="phone_number">Điện thoại</th>
 			<th>Action</th>
 		</tr>
 	</tfoot>
 </table>
 {{-- modal --}}
 <div id="myModal" class="modal fade" role="dialog">
 	<div class="modal-dialog">
 		<!-- Modal content-->
 		<div class="modal-content">
 			<div class="modal-header">
 				<button type="button" class="close" data-dismiss="modal">&times;</button>
 				<h4 class="modal-title">Đổi mật khẩu</h4>
 			</div>
 			<div class="modal-body">
 				<form action="" method="post" id="formm">
 					<div class="form-group">
 						<label for="exampleInputEmail1">Mật khẩu cũ</label>
 						<input type="password" class="form-control"
 						id="currentPassword" placeholder="Mật khẩu cũ" name="olderPassword"/>
 						<span class="bell" id="errorPassword">Mật khẩu tối thiểu 8 kí tự</span>
 					</div>
 					<div class="form-group">
 						<label for="exampleInputPassword1">Mật khẩu mới</label>
 						<input type="password" class="form-control"
 						id="newPassword" placeholder="Mật khẩu mới"/>
 						<span class="bell" id="q1">Mật khẩu tối thiểu 8 kí tự</span>
 					</div>
 					<div class="form-group">
 						<label for="exampleInputPassword1">Nhập lại mật khẩu</label>
 						<input type="password" class="form-control"
 						id="newPassword1" placeholder="Nhập lại mật khẩu"/>
 						<span class="bell" id="errorPass_1">Mật khẩu không khớp !!</span>
 					</div>
 					<button type="button" class="btn btn-default changePassword" id="submit">Thay đổi</button>
 				</form>
 			</div>
 			<div class="modal-footer">
 				<button type="button" class="btn btn-default" data-dismiss="modal">Quay lại</button>
 			</div>
 		</div>
 	</div>
 </div>
 {{-- end modal --}}
 @endsection
 @section('footer')
 @endsection