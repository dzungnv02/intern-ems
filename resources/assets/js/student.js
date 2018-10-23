$(function () {
    var tableStudent = $('#list-student').DataTable({
    	"columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]],
        ajax: {
	        url: 'api/get-list-student',
	        dataSrc: 'data',
	    },
	    columns: [
            { data: null},
            { data: 'name', name: 'name'},
            { data: 'email', name: 'email'},
            { data: 'address', name: 'address'},
            { data: 'mobile', name: 'mobile'},
            { data: 'birthday', name: 'birthday'},
            { data: 'gender', name: 'gender',
            	render : function(data) {
			        return data == '0' ? 'Nam' : 'Nữ';
			    }
        	},
            {
                'data': null,
                'render': function (data, type, row) {
                    return '<button studentID=\"'+row.id+'\" title=\"thêm học sinh vào lớp\"'+
                    'class=\"btn btn-primary StudentAdd\"><i class=\"fa fa-plus\" aria-hidden=\"true\"></i>'+
                    '</button> <button studentID=\"'+row.id+'\" title=\"sửa học sinh\"'+
                    'class=\"editStudent btn btn-warning\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i>'+
                    '</button> <button studentID=\"'+row.id+'\" title=\"xóa học sinh\" '+
                    'class=\"deleteStudent btn btn-danger\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></button>'
                }
            },
        ]
    });
    
    tableStudent.on( 'order.dt search.dt', function () {
        tableStudent.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    $('.add-student').click(function(){
    	$('#add-student').modal('show');
    });
    jQuery.datetimepicker.setLocale('vi');
    $('#birthday').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
    });
    $('#edit_birthday').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
    });

    var formStudent = $('#form-add');
    formStudent.validate({
		rules: {
			"name": {
				required: true,
			},
			"email": {
				required: true,
				email: true
			},
			"address": {
				required: true,
			},
			"mobile": {
				required: true,
				number: true
			},
			"birthday": {
				required: true,
				date: true
			},
		},
		messages: {
			"name": {
				required: "Bắt buộc nhập tên",
				maxlength: "Hãy nhập tối đa 15 ký tự"
			},
			"email": {
				required: "Bắt buộc nhập email",
				email: "Hãy nhập đúng định dạng email"
			},
			"address": {
				required: "Bắt buộc nhập địa chỉ",
			},
			"mobile": {
				required: "Bắt buộc nhập số điện thoại",
				number: "Hãy nhập số"
			},
			"birthday": {
				required: "Bắt buộc nhập ngày sinh",
				date: "Hãy nhập đúng định dạng ngày"
			},
		},
		highlight: function(element, errorClass) {
			$(element).closest(".form-group").addClass("has-error");
		},
		unhighlight: function(element, errorClass) {
			$(element).closest(".form-group").removeClass("has-error");
		},
		errorPlacement: function (error, element) {
			error.appendTo(element.parent().next());
		},
		errorPlacement: function (error, element) {
			if(element.attr("type") == "checkbox") {
				element.closest(".form-group").children(0).prepend(error);
			}
			else
				error.insertAfter(element);
		}
	});
	var formEditStudent = $('#form-edit');
    formEditStudent.validate({
		rules: {
			"name": {
				required: true,
			},
			"email": {
				required: true,
				email: true
			},
			"address": {
				required: true,
			},
			"mobile": {
				required: true,
				number: true
			},
			"birthday": {
				required: true,
				date: true
			},
		},
		messages: {
			"name": {
				required: "Bắt buộc nhập tên",
				maxlength: "Hãy nhập tối đa 15 ký tự"
			},
			"email": {
				required: "Bắt buộc nhập email",
				email: "Hãy nhập đúng định dạng email"
			},
			"address": {
				required: "Bắt buộc nhập địa chỉ",
			},
			"mobile": {
				required: "Bắt buộc nhập số điện thoại",
				number: "Hãy nhập số"
			},
			"birthday": {
				required: "Bắt buộc nhập ngày sinh",
				date: "Hãy nhập đúng định dạng ngày"
			},
		},
		highlight: function(element, errorClass) {
			$(element).closest(".form-group").addClass("has-error");
		},
		unhighlight: function(element, errorClass) {
			$(element).closest(".form-group").removeClass("has-error");
		},
		errorPlacement: function (error, element) {
			error.appendTo(element.parent().next());
		},
		errorPlacement: function (error, element) {
			if(element.attr("type") == "checkbox") {
				element.closest(".form-group").children(0).prepend(error);
			}
			else
				error.insertAfter(element);
		}
	});
    
    $('#store-student').click(function(e){
    	e.preventDefault();
    	var name = $('#name').val();
    	var student_code = $('#student_code').val();
    	var email = $('#email').val();
    	var address = $('#address').val();
    	var mobile = $('#mobile').val();
    	var birthday = $('#birthday').val();
    	var gender = $('#gender').val();
    	if(formStudent.valid()){
    		$.ajax({
	            type: 'POST',
	            url: 'api/add-student',
	            data: {name: name, email: email, address: address, mobile:mobile, birthday:birthday,
	            	gender:gender,student_code},
	            success: function(response){
	            	$('#add-student').modal('hide');
	            	$('#add-student').on('hidden.bs.modal', function(){
					    $(this).find('form')[0].reset();
					});
	            	tableStudent.ajax.reload();
	     			toastr.success('Thêm thành công!');
	            },
	        	error: function(data){
			        var errors = data.responseJSON;
			        $('.unique_email').html(errors.errors.email)
			        $('.unique_student_code').html(errors.errors.student_code)
			    }
	        });
    	}
    });

    $(document).on('click', '.deleteStudent', function(){
    	var id = $(this).attr('studentID');
        swal({
	        title: "Bạn có chắc muốn xóa?",
	        text: "Bạn sẽ không thể khôi phục lại bản ghi này!",
	        icon: "warning",
	        buttons: true,
	        dangerMode: true,
	    })
	    .then((willDelete) => {
	      	if (willDelete) {
      			$.ajax({
		            type: 'post',
		            url: 'api/delete-student',
		            data: {id:id},
		            success: function(response){
		            	if (response.code == 0) {
		            		toastr.error('Không thể xóa học viên này!');
		            	}else{
		            		tableStudent.ajax.reload();
		                	toastr.success('Xóa thành công!');
		            	}
		            }
		        })
	      	}
	    });
    });

    $(document).on('click', '.editStudent', function(){
        var id = $(this).attr('studentID');
        $.ajax({
            type: 'get',
            url: "api/edit-student",
            data: {id:id},
            success: function(response){
                $('#edit-student').modal('show');
                $('#edit_name').val(response['data'][0].name);
                $('#edit_student_code').val(response['data'][0].student_code);
                $('#edit_email').val(response['data'][0].email);
                $('#edit_address').val(response['data'][0].address);
                $('#edit_mobile').val(response['data'][0].mobile);
                $('#edit_birthday').val(response['data'][0].birthday);
                $('#edit_gender').val(response['data'][0].gender);
                $('#update-student').attr('data-id', response['data'][0].id);
            }
        })
    });

    $('#update-student').click(function(e){
    	e.preventDefault();
        var name = $('#edit_name').val();
        var student_code = $('#edit_student_code').val();
        var email = $('#edit_email').val();
        var address = $('#edit_address').val();
        var mobile = $('#edit_mobile').val();
        var birthday = $('#edit_birthday').val();
        var gender = $('#edit_gender').val();
        var id = $(this).attr('data-id');
        if(formEditStudent.valid()){
        	$.ajax({
	            type: 'post',
	            url: 'api/update-student',
	            data: {id:id,name: name, email: email,address:address,mobile:mobile,
	            	birthday:birthday,gender:gender,student_code:student_code},
	            success: function(response){
	                $('#edit-student').modal('hide');
	                tableStudent.ajax.reload();
	                toastr.success('Sửa thành công !');
	            },
	            error: function(data){
			        var errors = data.responseJSON;
			        $('.unique_email').html(errors.errors.email)
			        $('.unique_student_code').html(errors.errors.student_code)
			    }
	        });
        }
    });

    $(document).on('click', '.StudentAdd', function(){
    	IDstudent = $(this).attr('studentID');
    	$('#get_student_id').val(IDstudent);
    	$('#list-class').modal('show');
    });

    function getClassSize(data, type, dataToSet) {
	    return data.number_student + "/" + data.class_size;
	}
    var tableEnrollClass = $('#table-enroll-class').DataTable({
    	"columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]],
        ajax: {
	        url: 'api/get-list-enroll-class',
	        dataSrc: 'data',
	    },
	    columns: [
            { data: null},
            { data: 'class_code', name: 'class_code'},
            { data: 'name', name: 'name'},
            { data: 'course_name', name: 'course_name'},
            {data: getClassSize},
            {
                'data': null,
                'render': function (data, type, row) {
                    return '<button classID=\"'+row.id+'\" '+
                    'title=\"thêm học sinh vào lớp\"'+
                    'class=\"btn btn-success addStudentToClass\">Thêm</button>'
                }
            },
        ]
    });
    tableEnrollClass.on( 'order.dt search.dt', function () {
        tableEnrollClass.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    $(document).on('click','.addStudentToClass', function(){
    	var student_id = $('#get_student_id').val();
    	var class_id = $(this).attr('classID');
    	$.ajax({
            type: 'post',
            url: 'api/add-student-to-class',
            data: {student_id:student_id,class_id:class_id},
            success: function(response){
                if(response.code == 1){
           		  	$('#list-class').modal('hide');
                	tableEnrollClass.ajax.reload();
                	toastr.success(response.message);
                }else{
                	toastr.error('Lịch học bị trùng hoặc học sinh đã có trong lớp')
                }
            }
        });
    });
});