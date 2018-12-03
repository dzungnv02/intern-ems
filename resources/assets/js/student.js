$(function () {
	if ($('TABLE#list-student').length > 0) {
		var table_student = null;
		var save_student_button = $('DIV#student-form-modal DIV.modal-footer BUTTON#btnSave');
		var add_student_button = $('BUTTON.add-student');
		var add_parent_button = $("DIV#student-form-modal DIV.modal-body BUTTON.add-parent");
		var parent_info_panel = $("DIV#student-form-modal DIV.modal-body DIV.row#parent-info");
		var student_form = $("DIV#student-form-modal DIV.modal-body FORM#frmStudent");

		var init = () => {
			if ($.fn.dataTable.isDataTable('TABLE#list-student')) {
				table_student = $('TABLE#list-student').DataTable();
			} else {
				var buttons = '<button title="thêm học sinh vào lớp" class="btn btn-primary assign-to-class"><i class="fa fa-plus" aria-hidden="true"></i></button>\
							<button title="sửa học sinh" class="edit-student btn btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i></button>\
							<button title="xóa học sinh" class="del-student btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>';
				table_student = $('#list-student').DataTable({
					language: datatable_language,
					"columnDefs": [{
							"searchable": false,
							"orderable": false,
							"targets": 0
						},
						{
							targets: [7],
							"data": null,
							"visible": true,
							"defaultContent": buttons
						}
					],
					"order": [
						[1, 'asc']
					],
					ajax: {
						url: 'api/get-list-student',
						dataSrc: 'data',
					},
					columns: [{
							data: null
						},
						{
							data: 'name',
							name: 'name'
						},
						{
							data: 'email',
							name: 'email'
						},
						{
							data: 'address',
							name: 'address'
						},
						{
							data: 'mobile',
							name: 'mobile'
						},
						{
							data: 'birthyear',
							name: 'birthyear'
						},
						{
							data: 'gender',
							name: 'gender',
							render: function (data) {
								return data != null ? (data == '1' ? 'Nam' : 'Nữ') : '';
							}
						},
						{
							'data': null
						},
					]
				});

				table_student.on('order.dt search.dt', function () {
					table_student.column(0, {
						search: 'applied',
						order: 'applied'
					}).nodes().each(function (cell, i) {
						cell.innerHTML = i + 1;
					});
				}).draw();

				table_student.on('click', 'button.assign-to-class', function () {
					var data = table_student.row($(this).parents('tr')).data();
					console.log('assign-to-class', data);
				});

				table_student.on('click', 'button.edit-student', function () {
					var data = table_student.row($(this).parents('tr')).data();
					window.location.href = '/student/detail?student_id=' + data.id;
					//show_student_form(data.id);
				});

				table_student.on('click', 'button.del-student', function () {
					var data = table_student.row($(this).parents('tr')).data();
					show_delete_student_confirm_modal(data);
					console.log('del-student', data);
				});
			}

			$(save_student_button).bind('click', (e) => {
				$(e.target).button('loading');
				$(e.target).prop('disabled', true);
				save_student();
			});

			$(add_student_button).bind('click', (e) => {
				var student_id = null;
				show_student_form();
			});

			$(add_parent_button).bind('click', (e) => {
				$(parent_info_panel).show();
			});

			$('DIV#student-form-modal').on('show.bs.modal', (e) => {
				console.log(e);
			})
		}

		var show_assign_class_modal = (student_id) => {}
		var get_class_list_to_assign = (student_id) => {}
		var assign_to_class = (student_id, class_id) => {}

		var show_student_form = (student_id) => {
			var modal_title = 'Thêm mới học sinh';
			$("DIV#student-form-modal").find('FORM#frmStudent INPUT#id').val();
			$("DIV#student-form-modal").find('FORM#frmStudent INPUT#crm_id').val();

			if (student_id != null) {
				modal_title = 'Sửa thông tin học sinh';
				$("DIV#student-form-modal").find('FORM#frmStudent INPUT#id').val(student_id);
				get_student(student_id, (student, parent) => {
					reset_student_form(student, parent);
					$("DIV#student-form-modal").modal('show');
				});
			} else {
				reset_student_form(null, null);
				$("DIV#student-form-modal").modal('show');
			}

			$("DIV#student-form-modal").find('.modal-title').html(modal_title);
		}

		var reset_student_form = (student, parent) => {
			var form_inputs = $(student_form).find('INPUT');
			if (student == null) {
				$(form_inputs).val('');
				$(student_form).find('SELECT#gender').val('0').change();
				return;
			}

			$(form_inputs).each((index, el) => {
				var field = el.id.indexOf('parent-') == 0 ? el.id.substr(7, el.id.length) : el.id;
				if (student != null) {
					if (student[field] != undefined && el.id.indexOf('parent-') == -1) {
						$(el).val(student[field]);
					} else if (student[field] == undefined) {
						$(el).val('');
					}
				}

				if (parent != null) {
					if (parent[field] != undefined && el.id.indexOf('parent-') == 0) {
						$(el).val(parent[field]);
					} else if (parent[field] == undefined) {
						$(el).val('');
					}
				}
			});

			$(student_form).find('SELECT#gender').val(student.gender).change();
		}

		var get_student = (student_id, callback) => {
			$.ajax({
				url: 'api/get-student',
				data: {
					'id': student_id
				},
				method: 'GET',
				success: (response) => {
					var student = response.data.student;
					var parent = response.data.parent;
					callback(student, parent);
				}
			});
		}

		var validate = (data) => 
		{
			var result = false;
			if (data.student.name == '') {

			}
		}

		var save_student = () => {

			var data = {
				"student": {
					"id": "",
					"name": "",
					"crm_id": "",
					"email": "",
					"mobile": "",
					"birthday": "",
					"birthyear": "",
					"address": "",
					"gender": "",
				},
				"parent": {
					"id": "",
					"fullname": "",
					"email": "",
					"phone": "",
					"role": "",
					"facebook": "",
				}
			}

			var form_parent_inputs = $(student_form).find('INPUT[id^="parent-"]');
			var form_student_inputs = $(student_form).find('INPUT:not([id^="parent-"])');
			form_student_inputs.push($(student_form).find('SELECT#gender')[0]);

			$(form_parent_inputs).each((index, el) => {
				var field = el.id.substr(7, el.id.length);
				if (field == "search") return;
				data.parent[field] = $(el).val();
			});

			$(form_student_inputs).each((index, el) => {
				data.student[el.id] = $(el).val();
			});

			$.ajax({
				url: 'api/save-student',
				data: JSON.stringify(data),
				contentType: 'application/json',
				method: 'POST',
				success: (response) => {
					console.log(response);
					table_student.ajax.reload();
					$("DIV#student-form-modal").modal('hide');
				}
			}).done(() => {
				$(save_student_button).prop('disabled', false);
				$(save_student_button).button('reset');
			});
		}

		var show_delete_student_confirm_modal = (data) => {
			modalConfirm((confirm) => {
				if (confirm) {
					delete_student(data.id);
				}
			}, 'Bạn có muốn xoá học sinh <strong>' + data.name + '</strong> không?')
		}

		var delete_student = (student_id) => {
			console.log(student_id);
		}

		var modalConfirm = (callback, message) => {
			if (message != undefined) {
				$("#confirm-delete DIV.modal-header H5.modal-title").html(message);
			}
			$("#confirm-delete").modal('show');

			$("#modal-btn-yes").unbind('click').bind("click", function () {
				callback(true);
				$("#confirm-delete").modal('hide');
			});

			$("#modal-btn-no").unbind('click').bind("click", function () {
				callback(false);
				$("#confirm-delete").modal('hide');
			});
		};

		init();
	}
});