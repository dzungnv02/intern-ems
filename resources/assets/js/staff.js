import { finished } from "stream";

$(document).ready(function () {
	
	/**
	*validate form add
	*/
	function validateEmail(email) {
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}

	function getBranchList(currentBranchId) {
		$.ajax('/api/branch/list',{
			type: 'GET',
			success: function (response) {
				var select = $('SELECT#branch_id');
				$(select).find('OPTION').not(':first').remove();
				if (response.data.length > 0) {
					for (var i = 0; i < response.data.length; i++) {
						var branch = response.data[i];
						var opt = $('<option></option>', {value:branch.id, text:branch.branch_name});
						if (currentBranchId == branch.id) $(opt).prop('selected','selected');
						$(select).append(opt);
					}
				}
			},
			error: function (e) {
				console.log(e);
			}
		});
	}

	function validate() {
		var isValid = false;
		var $result = $("#errorEmail");
		var email = $("#inputEmail").val();
		var password = $('#inputPassword').val();
		var password_1 = $('#inputPassword_1').val();
		var name = $('#inputName').val();
		var phoneNumber = $('#inputPhone').val();

		$('SPAN.bell [id^="error"]').addClass('hidden');
		var emailValid = validateEmail(email);
		if (emailValid) {
			isValid = true;
			$result.addClass('hidden');
		} else {
			$result.removeClass('hidden');
			isValid = false;
		}
		if (password.length <= 8 || password == "") {
			$('#errorPassword').addClass('hidden');
			isValid = true;
		}
		else {
			$('#errorPassword').removeClass('hidden');
			isValid = false;
		}

		if (password != password_1) {
			$('#errorPassword_1').removeClass('hidden');
			isValid = true;
		} else {
			$('#errorPassword_1').addClass('hidden');
			isValid = false;
		}

		if (name) {
			$('#errorName').addClass('hidden');
			isValid = true;
		} else {
			$('#errorName').removeClass('hidden');
			isValid = false;
		}

		if (phoneNumber.length >= 15 || phoneNumber == "") {
			$('#errorPhone').removeClass('hidden');
			isValid = false;
		}
		else {
			$('#errorPhone').addClass('hidden');
			isValid = true;
		}

		return isValid;
	}

	/*
	* validate form edit password
	**/
	function validateForm() {
		var password = $("#newPassword").val();
		var newPassword1 = $('#newPassword1').val();
		if (!password || password.length < 8) {
			$("#errorPassword").css("display", "block");
		}
		else {
			$("#errorPassword").css("display", "none");
		}
		if (password == newPassword1) {
			$("#q1").css("display", "none");
		}
		else {
			$("#errorPass_1").css("display", "block");
		}
	}

	$("#submit").bind("click", validateForm);

	/*
	* ajax add staff
	*/
	$('BUTTON#addStaff').click(function (event) {

		var isValided = validate();
		if (!isValided) {
			return false;
		}

		var email = $('#inputEmail').val();
		var password = $('#inputPassword').val();
		var password_1 = $('#inputPassword_1').val();
		var name = $('#inputName').val();
		var gender = $('#inputGender').val();
		var birthDate = $('#inputBirthDate').val();
		var address = $('#inputAddress').val();
		var phone = $('#inputPhone').val();
		var branch_id = $('SELECT#branch_id').val();
		
		var formData = new FormData();
		formData.append('email', email);
		formData.append('password', password);
		formData.append('name', name);
		formData.append('gender', gender);
		formData.append('birth_date', birthDate);
		formData.append('address', address);
		formData.append('phone_number', phone);
		formData.append('branch_id', branch_id);

		$.ajax({
			url: '/api/add-staff',
			type: 'POST',
			contentType: false,
			processData: false,
			data: formData,
			success: function (response) {
				//location.reload();
				alert(response.message);
			},
			error: function (e) {
				alert("Can not add staff !!");
			}
		})
	});

	getBranchList(null);

	if ($('TABLE#staff_list').length == 0) {
		return false;
	}

	/*
	* ajax get list staff
	*/
	var t = $('TABLE#staff_list').DataTable({
		"ajax": 'api/get-list-staff',
		"responsive": true,
		"columns": [
			{ "data": null },
			{ "data": "name" },
			{ "data": "email" },
			{
				"data": "gender",
				render: function (data, type, row) {
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
					return row.image ? '<img style=\"width:200px;\" src="' + img + row.image + '">' : '';
				}
			},
			{ "data": "birth_date" },
			{ "data": "address" },
			{ "data": "phone_number" },
			{ "data": null }
		],
		// "columnDefs": [ ],
		// "order": [[ 1, 'asc' ]],
		"columnDefs": [{
			"searchable": false,
			"orderable": false,
			"targets": 0
		}, {
			"targets": -1,
			"data": null,
			"defaultContent": "<button type='button' href=" + asset + "staff/edit" + " class=\"btn btn-warning _action fa fa-pencil-square-o\" title=\"Chỉnh sửa\" id=\"edit_staff\"></button>"
				+ "<a href=\"#\" class=\"btn btn-danger _action fa fa-trash\" title=\"Xoa\" type=\"button\" id=\"delete\" ></a>"
				+ "<a href=\"#\" title=\"Đôi mật khẩu\" class=\"btn btn-info _action fa fa-key\" data-toggle=\"modal\" data-target=\"#myModal\" id=\"change_password\"></a>"
		}]
	});
	// var t = $('#example').DataTable()
	t.on('order.dt search.dt', function () {
		t.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
			cell.innerHTML = i + 1;
		});
	}).draw();

	$('TABLE#staff_list tbody').on('click', '#delete', function () {
		var table = $('TABLE#staff_list').DataTable();
		var data = table.row($(this).parents('tr')).data();
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
				else {
					toastr.warning('Bạn đã hủy!');
				}
			})
	})
	$('TABLE#staff_list').on('click', '#change_password', function () {
		var table = $('#example').DataTable();
		var data = table.row($(this).parents('tr')).data();
		data = data.id;
		editPassword(data);
	});

	function editPassword(data) {
		$('#formm').on('click', '#submit', function () {
			var current_password_input = $('#currentPassword').val();
			var new_password = $('#newPassword').val();
			var new_password_1 = $('#newPassword1').val();
			if (new_password == new_password_1 && new_password.length >= 8) {
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
	$('TABLE#staff_list').on('click', '#edit_staff', function () {
		var table = $('TABLE#staff_list').DataTable();
		var data = table.row($(this).parents('tr')).data();
		//data = data.id;
		if (typeof (Storage) !== "undefined") {
			//localStorage.setItem("id", data);
		} else {
			//alert('Trình duyệt của bạn không hỗ trợ');
		}
	});

	
});

$('#editStaff_1').click(function (event) {
	var id = localStorage.getItem("id");
	var email = $('#inputEmail3').val();
	var password = $('#inputPassword').val();
	var password_1 = $('#inputPassword_1').val();
	var name = $('#inputName').val();
	var gender = $('#inputGender').val();
	var birthDate = $('#inputBirthDate').val();
	var address = $('#inputAddress').val();
	var phone = $('#inputPhone').val();
	if (email != "" && password != "" && name != "" && gender != "" && birthDate != "" &&
		address != "" && password.length >= 8 && password == password_1 && $('#inputFile')[0].files[0]
	) {
		var formData = new FormData();
		formData.append('id', id);
		formData.append('email', email);
		formData.append('password', password);
		formData.append('name', name);
		formData.append('gender', gender);
		formData.append('birth_date', birthDate);
		formData.append('address', address);
		formData.append('phone_number', phone);
		formData.append("file", $('#inputFile')[0].files[0]);
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
	} else {
		alert('Thong tin dien vao khong hop le !!!');
	}
});




