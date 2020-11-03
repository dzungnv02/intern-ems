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
		if ($('FORM#frmStaff').length == 0 && $('FORM#frmEditStaff').length == 0) return;

		$.ajax('/api/branch/list', {
			type: 'GET',
			success: function (response) {
				var select = $('SELECT#branch_id');
				$(select).find('OPTION').not(':first').remove();
				var current_staff = localStorage.getItem('current_staff');
				if (current_staff != undefined) {
					currentBranchId = JSON.parse(current_staff).branch_id;
				}
				if (response.data.length > 0) {
					for (var i = 0; i < response.data.length; i++) {
						var branch = response.data[i];
						var opt = $('<option></option>', { value: branch.id, text: branch.branch_name });
						if (currentBranchId == branch.id) $(opt).prop('selected', 'selected');
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
		if ($('FORM#frmEditStaff').length == 0) {
			if (password.length >= 8) {
				$('#errorPassword').addClass('hidden');
			}
			else {
				$('#errorPassword').removeClass('hidden');
				isValid = false;
			}

			if (password != password_1) {
				$('#errorPassword_1').removeClass('hidden');
				isValid = false;
			} else {
				$('#errorPassword_1').addClass('hidden');
			}
		}

		if (name) {
			$('#errorName').addClass('hidden');
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
		var role = $('#role').val();
		var gender = $('#inputGender').val();
		var birthDate = $('#inputBirthDate').val();
		var address = $('#inputAddress').val();
		var phone = $('#inputPhone').val();
		var branch_id = $('SELECT#branch_id').val();

		var formData = new FormData();
		formData.append('email', email);
		formData.append('password', password);
		formData.append('name', name);
		formData.append('role', role);
		formData.append('gender', gender);
		formData.append('birth_date', birthDate);
		formData.append('address', address);
		formData.append('phone_number', phone);
		formData.append('branch_id', branch_id);

		$.ajax({
			url: '/api/add-staff',
			method: "POST",
			contentType: false,
			processData: false,
			data: formData,
			success: function (response) {
				//location.reload();
				location.href = '/staff-list';
				alert(response.message);
			},
			error: function (e) {
				alert("Can not add staff !!");
			}
		})
	});

	getBranchList(null);

	if ($('FORM#frmEditStaff').length > 0) {
		var form = $('FORM#frmEditStaff');

		var load_edit = () => {
			var data = JSON.parse(localStorage.getItem('current_staff'));
			$(form).find('INPUT#id').val(data.id);
			$(form).find('INPUT#inputEmail').val(data.email);
			$(form).find('SELECT#role').val(data.role);
			$(form).find('INPUT#inputName').val(data.name);
			$(form).find('INPUT#inputPhone').val(data.phone_number);
			$(form).find('SELECT#inputGender').val(data.gender);
			$(form).find('INPUT#inputBirthDate').val(data.birth_date);
			$(form).find('INPUT#inputAddress').val(data.address);
		}

		$(form).find('BUTTON#btnSave').on('click', (e) => {
			var isValided = validate();
			if (!isValided) {
				return false;
			}

			var id = $(form).find('INPUT#id').val();
			var email = $('#inputEmail').val();
			var name = $('#inputName').val();
			var role = $('#role').val();
			var gender = $('#inputGender').val();
			var birthDate = $('#inputBirthDate').val();
			var address = $('#inputAddress').val();
			var phone = $('#inputPhone').val();
			var branch_id = $('SELECT#branch_id').val();

			var data = {
				'id' : id,
				'email': email,
				'name': name,
				'role': role,
				'gender': gender,
				'birth_date': birthDate,
				'address': address,
				'phone_number': phone,
				'branch_id': branch_id
			}

			$.ajax({
				url: '/update-staff',
				method: "PATCH",
				contentType: 'application/json',
				data: JSON.stringify(data),
				success: function (response) {
					localStorage.removeItem('current_staff');
					location.href = '/staff-list';
				},
				error: function (e) {
					alert("Can not save staff !!");
				}
			})
		});

		load_edit();
	}

	if ($('TABLE#staff_list').length == 0) {
		return false;
	}

	localStorage.removeItem('current_staff');

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
				+ "&nbsp;<button title=\"Đôi mật khẩu\" class=\"btn btn-info _action fa fa-key\" data-toggle=\"modal\" data-target=\"#changePasswdModal\" id=\"change_password\"></button>"
				+ "&nbsp;<button class=\"btn btn-danger _action fa fa-trash\" title=\"Xoa\" type=\"button\" id=\"delete\" ></button>"
				
		}]
	});

	t.on('order.dt search.dt', function () {
		t.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
			cell.innerHTML = i + 1;
		});
	}).draw();

	t.on('click', 'button#change_password', function () {
		var data = t.row($(this).parents('tr')).data();
		$('#changePasswdModal FORM#frmChangePasswd INPUT#staff_id').val(data.id);
		$('#changePasswdModal DIV.modal-header SPAN#staff-name').text(data.name);
		
	});

	$('TABLE#staff_list').on('click', '#edit_staff', function () {
		var table = $('TABLE#staff_list').DataTable();
		var data = table.row($(this).parents('tr')).data();
		localStorage.setItem('current_staff', JSON.stringify(data));
		location.href = '/staff-edit';
	});

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
						method: "POST",
						contentType: false,
						processData: false,
						data: formData,
						success: function (response) {
							location.reload();
						}
					});
				}
			})
	});

	$('#changePasswdModal').on('show.bs.modal', (e) => {
		$('#changePasswdModal').find('SPAN.bell').hide();
	});

	$('#changePasswdModal').on('hide.bs.modal', (e) => {
		$('#changePasswdModal FORM#frmChangePasswd')[0].reset();
	});

	$('#frmChangePasswd BUTTON#submit').on('click', function () {
		var id = $('#changePasswdModal FORM#frmChangePasswd INPUT#staff_id').val();
		var new_password = $('#changePasswdModal FORM#frmChangePasswd #newPassword').val();
		var new_password_1 = $('#changePasswdModal FORM#frmChangePasswd #newPassword1').val();
		if (new_password == new_password_1 && new_password.length >= 8) {
			var formData = new FormData();
			formData.append('id', id);
			formData.append('newPassword', new_password);
			$.ajax({
				url: '/api/edit-password-staff',
				method: "POST",
				contentType: false,
				processData: false,
				data: formData,
				success: function (response) {
					$('#changePasswdModal').modal('hide');
				},
				error: function (response) {
					alert('Can not edit info this !!');
					location.reload();
				}
			})
		}
		else {
			if (new_password.length < 8) {
				$('#changePasswdModal').find('SPAN.bell#q1').show();
			}
			else {
				$('#changePasswdModal').find('SPAN.bell#q1').hide();
			}

			if (new_password != new_password_1) {
				$('#changePasswdModal').find('SPAN.bell#q2').show();
			}
			else {
				$('#changePasswdModal').find('SPAN.bell#q2').hide();
			}
		}
	})


});




