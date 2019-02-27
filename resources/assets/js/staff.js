$(document).ready(function() {
	$(document).ready(function() {
		function uploadImg(selectorClick, selectorShow) {
			$(document).on('change', '#' + selectorClick, function() {
				if (this.files[0].name) {
					var reader = new FileReader();
					reader.onload = function(e) {
						$('#' + selectorShow).attr('src', e.target.result);
					}
					reader.readAsDataURL(this.files[0]);
				}
			});
		};
		uploadImg('inputFile','showImg');
	/**
	*validate form add
	*/
	function validateEmail(email) {
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}

	function validate() {
		var $result = $("#errorEmail");
		var email = $("#inputEmail3").val();
		var password = $('#inputPassword').val();
		var password_1 = $('#inputPassword_1').val();
		var name = $('#inputName').val();
		var phoneNumber =$('#inputPhone').val();
		
		var img = $('#inputFile').val().split('.').pop().toLowerCase();

		if (validateEmail(email)) {
			$result.css("display", "none");
		} else {
			$result.css("display", "block");
		}
		if(password.length <= 8 || password == ""){
			$('#errorPassword').css('display','block');
		}
		else{
			$('#errorPassword').css('display','none');
		}
		if (password != password_1) {
			$('#errorPassword_1').css('display','block');
		}else {
			$('#errorPassword_1').css('display','none');
		}
		if (name) {
			$('#errorName').css('display','none');
		}else {
			$('#errorName').css('display','block');
		}

		if(phoneNumber.length >=15 || phoneNumber == ""){
			$('#errorPhone').css('display', 'block');
		}
		else {
			$('#errorPhone').css('display','none');
		}
		
		if( img =="png" || img == "jpg" || img == "bmp" || img == "jpeg" || img == "gif"){
			$('#errorImg').css('display', 'none');
		}
		else {
			$('#errorImg').css('display', 'block');	
		}
	}

	$("#addStaff").bind("click", validate);
	});
	/*
	* validate form edit password
	**/
	function validateForm() {
		var password = $("#newPassword").val();
		var newPassword1 = $('#newPassword1').val();
		if(!password || password.length < 8){
			$("#errorPassword").css("display", "block");
		}
		else {
			$("#errorPassword").css("display", "none");
		}
		if( password == newPassword1){
			$("#q1").css("display", "none");
		}
		else{
			$("#errorPass_1").css("display", "block");
		}
	}
	$("#submit").bind("click", validateForm);
	/*
	* ajax add staff
	*/
	$('#addStaff').click(function(event) {
		var email = $('#inputEmail3').val();
		var password = $('#inputPassword').val();
		var password_1 = $('#inputPassword_1').val();
		var name = $('#inputName').val();
		var gender = $('#inputGender').val();
		var birthDate = $('#inputBirthDate').val();
		var address = $('#inputAddress').val();
		var phone = $('#inputPhone').val();
		if(email !="" && password !="" && name !="" && gender!="" && birthDate !=""&& 
			address !="" && password.length >= 8 && password == password_1 && $('#inputFile')[0].files[0]
			){
			var formData = new FormData();
		formData.append('email', email);
		formData.append('password', password);
		formData.append('name', name);
		formData.append('gender', gender);
		formData.append('birth_date', birthDate);
		formData.append('address', address);
		formData.append('phone_number', phone);
		formData.append("file",$('#inputFile')[0].files[0]);

		$.ajax({
			url: '/api/add-staff',
			type: 'POST',
			contentType: false, 
			processData: false,
			data: formData,
			success: function (response) {
				location.reload();
				alert(response.message);
			},
			error: function (e) {
				alert("Can not add staff !!");
			}
		})
	}
});

	if ($('TABLE#staff_list').length == 0) return false;
	/*
	* ajax get list staff
	*/
	var t = $('TABLE#staff_list').DataTable( {
		"ajax": 'api/get-list-staff',
		"responsive": true,
		"columns": [
			{ "data": null },
			{ "data": "name" },
			{ "data": "email" },
			{ 
				"data": "gender", 
					render : function(data, type, row){
						var gender;
						switch (row.gender) {
							case 0:
								gender = "Nữ";
								break;
							case 1:
								gender = "Nam";
								break;
							case 2:
								gender = "Khác";
								break;
							default:
								gender = "";
								break;
							}
							return gender;
						}

					},
				{ 
					"render": function (data, type, row, meta) {
						return row.image ? '<img style=\"width:200px;\" src="'+img+row.image+'">' : '';
					}
			},
			{ "data": "birth_date" },
			{ "data": "address" },
			{ "data": "phone_number" },
			{ "data": null }
		],
		// "columnDefs": [ ],
		// "order": [[ 1, 'asc' ]],
		"columnDefs": [ {
			"searchable": false,
			"orderable": false,
			"targets": 0
		} ,{
			"targets": -1,
			"data": null,
			"defaultContent": "<a href=" + asset + "staff/edit" + " class=\"btn btn-warning _action fa fa-pencil-square-o\" title=\"Chỉnh sửa\" id=\"editStaffid\"></a>"
			+"<a href=\"#\" class=\"btn btn-danger _action fa fa-trash\" title=\"Xoa\" type=\"button\" id=\"delete\" ></a>"
			+"<a href=\"#\" title=\"Đôi mật khẩu\" class=\"btn btn-info _action fa fa-key\" data-toggle=\"modal\" data-target=\"#myModal\" id=\"editPassword\"></a>"
		} ]
	});
	// var t = $('#example').DataTable()
	t.on( 'order.dt search.dt', function () {
		t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
			cell.innerHTML = i+1;
		} );
	} ).draw();

	$('TABLE#staff_list tbody').on( 'click', '#delete', function () {
		var table = $('TABLE#staff_list').DataTable(); 
		var data = table.row( $(this).parents('tr') ).data();
		data = data.id;
		var formData = new FormData();
		formData.append('id', data);
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
					url: '/api/delete-staff',
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
	})
	$('TABLE#staff_list').on( 'click', '#editPassword', function () {
		var table = $('#example').DataTable(); 
		var data = table.row( $(this).parents('tr') ).data();
		data = data.id;
		editPassword(data);
	});

	function editPassword(data){
		$('#formm').on( 'click', '#submit', function () {
			var current_password_input 	= $('#currentPassword').val();
			var new_password 			= $('#newPassword').val();
			var new_password_1 			= $('#newPassword1').val();
			if( new_password == new_password_1 && new_password.length >=8){
				var formData = new FormData();
				formData.append('id', data);
				formData.append('currentPassword', current_password_input);
				formData.append('newPassword', new_password);
				$.ajax({
					url: '/api/edit-password-staff',
					type: 'POST',
					contentType: false, 
					processData: false,
					data: formData,
					success: function (response) {
						alert(response.message);
						location.reload();
					},
					error: function (response) {
						alert('Can not edit info this !!');
						location.reload();
					}
				})
			}
		})
	}

	/*
	* ajax edit staff
	*/
	$('TABLE#staff_list').on( 'click', '#editStaffid', function () {
		var table = $('#example').DataTable(); 
		var data = table.row( $(this).parents('tr') ).data();
		data = data.id;
		if (typeof(Storage) !== "undefined") {
			localStorage.setItem("id", data);
		} else {
			alert('Trình duyệt của bạn không hỗ trợ');
		}
	});
});

$('#editStaff_1').click(function(event) {
	var id = localStorage.getItem("id");
	var email = $('#inputEmail3').val();
	var password = $('#inputPassword').val();
	var password_1 = $('#inputPassword_1').val();
	var name = $('#inputName').val();
	var gender = $('#inputGender').val();
	var birthDate = $('#inputBirthDate').val();
	var address = $('#inputAddress').val();
	var phone = $('#inputPhone').val();
	if(email !="" && password !="" && name !="" && gender!="" && birthDate !=""&& 
		address !="" && password.length >= 8 && password == password_1 && $('#inputFile')[0].files[0]
		){
		var formData = new FormData();
	formData.append('id', id);
	formData.append('email', email);
	formData.append('password', password);
	formData.append('name', name);
	formData.append('gender', gender);
	formData.append('birth_date', birthDate);
	formData.append('address', address);
	formData.append('phone_number', phone);
	formData.append("file",$('#inputFile')[0].files[0]);
	$.ajax({
		url: '/api/edit-staff',
		type: 'POST', 
		contentType: false, 
		processData: false,
		data: formData,
		success: function (response) {
			location.reload();
			alert(response.message);
		},
		error: function (e) {
			alert("Can not edit staff !!");
		}
	})
}else {
	alert('Thong tin dien vao khong hop le !!!');
}
});




