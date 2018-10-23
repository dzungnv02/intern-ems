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
	<a href="{{ asset('teacher-add') }}" class="btn btn-info">Thêm mới</a><br><br>
	<div class="card-body table-reponsive">
		<table class="table table-bordered table-striped" id="list-teacher-1">
			<thead>
				<tr>
					<th>STT</th>
					<th>Tên giáo viên</th>
					<th>Địa chỉ</th>
					<th>Số điện thoại</th>
					<th>Ngày sinh</th>
					<th>Quốc tịch</th>
					<th>Thông tin thêm</th>
					<th>Action</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>STT</th>
					<th>Tên giáo viên</th>
					<th>Địa chỉ</th>
					<th>Số điện thoại</th>
					<th>Ngày sinh</th>
					<th>Quốc tịch</th>
					<th>Thông tin thêm</th>
					<th>Action</th>
				</tr>
			</tfoot>
		</table>
	</div></br>
	@endsection
	@section('footer')
	{{-- <script src="{{ asset('js/teacher.js') }}"></script> --}}
	<script>
		var asset = "{{ asset('') }}";
		var s = `$('#delete').click(function(event) {
			var formData = new FormData();
			formData.append('id', id);

			swal({
				title: "Bạn có chắc muốn xóa?",
				text: "Bạn sẽ không thể khôi phục lại bản ghi này!",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete, data) => {
				if (willDelete) {
					$.ajax({
						url: '/api/delete-teacher',
						type: 'POST',
						contentType: false, 
						processData: false,
						data: formData,
						success: function (response) {
							location.reload();
							toastr.warning(response.message);
						},
						error: function (response) {
							toastr.warning(response.message);
						}
					});
				}
				else{
					toastr.warning('Bạn đã hủy!');
				}
			})
		}
	});`

</script>
@endsection
{{-- var table = $('#myTable').DataTable();
 
$('#myTable').on( 'click', 'tr', function () {
    var id = table.row( this ).id();
 
    alert( 'Clicked row id '+id );
} ); --}}