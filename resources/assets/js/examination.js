$(function () { 
	var url_string = window.location.href;
	var url = new URL(url_string);
	var classid = url.searchParams.get("classid");
	var Table  = $('#list-exam').DataTable({
		"columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]],
		paging: true,
		searching: true,
		"bDestroy": true,
		ajax: {
			url: 'api/get-list-exam',
			data: {classid:classid},
			dataSrc: 'data',
	},
			"columns": [
				{ "data": null },
				{ "data": "name" },
				{ "data": "name_class" },
				{ "data": "start_day" },
				{ "data": "duration" },
				{ "data": "note" },
				{ "data": function(data, type, full) {
					return ' <button type="button" examid="'+data.id+'"  class="button-set-point btn btn-success">\
					<i class="fa fa-tasks"  aria-hidden="true" title="Thêm điểm kỳ thi"></i></button>\
					<button type="button" examid="'+data.id+'"  class="button-get-point btn btn-info">\
					<i class="fa fa-eye"  aria-hidden="true" title="Xem điểm kỳ thi"></i></button>\
					<button id="edit" type="button" class="button-edit-exam btn btn-warning"  examid="'+data.id+'">\
					<i class="fa fa-pencil-square" aria-hidden="true" title="Sửa kỳ thi"></i></button>\
					<button type="button" examid="'+data.id+'" class="button-del-exam btn btn-danger">\
					<i class="fa fa-trash-o"  aria-hidden="true" title="Xóa kỳ thi"></i></button>' 
				}
			}
		]

	});
	Table.on( 'order.dt search.dt', function () {
        Table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
	} ).draw();
	jQuery.datetimepicker.setLocale('vi');
    $('#start_day').datetimepicker({
		format:'Y-m-d H:i',
        minDate: '-1970-01-1',
	});
});
$(document).on('click','.button-add',function(e){
	e.preventDefault();
	$('#model-add').modal('show');
	$("#name-class").empty();
	$.ajax({
		dataType : 'json',
		type : 'get',
		url : 'api/get-nameclass',
		success:function(response){
			$.each(response.data, function () {
				$("#name-class").append("<option id='class_id' value="+this.id+">"+this.name+"</option>")
			});			
		}
	})
			//validate form    
			$('#form-add-exam').validate(
				{
					rules : {
					name : {
						required : true,
						minlength: 10
		
					},
					start_day : {
						required : true,
					//	min : "date('Y-m-d')",
				
						},
					duration : {
						required : true,
						number: true,
				
						},
					note : {
						required : true,
						minlength: 10
					},
					},
					messages: {
					name : {
						required : "Không được đế trống",
						minlength : "Không đủ 10 ký tự"
					},
					start_day : {
						required : "Không được đế trống",
					//	min : "Ngày phải lớn hơn ngày hiện tại"
						},
						duration : {
						required : "Không được đế trống",
						number : "Phải là số"
						}, 
						note : {
						required : "Không được đế trống",
						minlength : "Không đủ 10 ký tự"
						}
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
				})
});
//add exam

		//add exam      
	$('#add-exam').click(function(event){
	  event.preventDefault();
	  var name  = $('#name').val();
	  var start_day = $('#start_day').val();
	  var duration = $('#duration').val();
	  var note = $('#note').val();
	  var class_id = $('#class_id').val();
	  if($('#form-add-exam').valid()){
			$.ajax({
			url :"api/create-exam",
			type: "POST",
			data : {name: name,start_day: start_day,duration: duration,note: note,class_id: class_id},
			dataType:"json",
			success:function(response){ 
					$("#model-add").modal("hide");
					//$('#form-add-exam').dialog("close")
					$('#list-exam').DataTable().ajax.reload();
					if(response.code == 1){
						toastr.success('Thêm kỳ thành công');
						}else{
							toastr.error('Lỗi không thể thêm!');
						}
					$("#form-add-exam")[0].reset();
				}
			})
		}
	})
//del exam
$(document).on('click','.button-del-exam',function(){

	swal({
		title: "Bạn có chắc muốn xóa?",
		text: "Bạn sẽ không thể khôi phục lại bản ghi này!",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {  
			var id = $(this).attr("examid");
			console.log(id);	
				$.ajax({
						dataType : 'json',
						type : 'post',
						url : 'api/delete-exam',
						data : {id:id},
						success: function(response){
							if(response.code == 1){
								$('#list-exam').DataTable().ajax.reload();
								toastr.success('Xóa thành công!');
							}else{
								toastr.error(response.message)
							}
							
						}
					})
					
				
			}
			else{
				toastr.warning('Bạn đã hủy!');
				  }
		})
	})
//edit exam edit-exam
$(document).on('click','.button-edit-exam',function(e){
	e.preventDefault();
	$('#edit-exam').modal('show');
	var id = $(this).attr("examid");	
	$("#ename_class").empty();
	jQuery.datetimepicker.setLocale('vi');
    $('#estart_day').datetimepicker({
		format:'Y-m-d H:i',
        minDate: '-1970-01-1',
	});
			$.ajax({
				dataType : 'json',
				type : 'post',
				url : 'api/edit-exam',
				data : {id:id},
				resetForm: true,
				success: function(response){
					$('#update-exam-hd').val(id);
					var classid = response.data['class_id'];
						$('#ename').val(response.data['name']);
						$('#estart_day').val(response.data['start_day']);
						$('#eduration').val(response.data['duration']);
						$('#enote').val(response.data['note']);
						$.ajax({
							dataType : 'json',
							type : 'get',
							url : 'api/get-nameclass',
							success:function(response){
								$.each(response.data, function () {
									if(this.id == classid ){
										$("#ename_class").append("<option id='class_id' value="+this.id+" selected>"+this.name+"</option>")
									}else{
										$("#ename_class").append("<option id='class_id' value="+this.id+">"+this.name+"</option>")
									}
								});
								
							},
						})
				}
			})
			//validate form exam
			$('#form-edit-exam').validate(
				{
					rules : {
					name : {
						required : true,
						minlength: 10

					},
					start_day : {
						required : true,
					//	min : "date('Y-m-d')",
				
						},
					duration : {
						required : true,
						number: true,
				
						},
					note : {
						required : true,
						minlength: 10
					},
					},
					messages: {
					name : {
						required : "Không được đế trống",
						minlength : "Không đủ 10 ký tự"
					},
					start_day : {
						required : "Không được đế trống",
					//	min : "Ngày phải lớn hơn ngày hiện tại"
						},
						duration : {
						required : "Không được đế trống",
						number : "Phải là số"
						}, 
						note : {
						required : "Không được đế trống",
						minlength : "Không đủ 10 ký tự"
						}
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
				}
				)    
		})

//update exam   
			// Update      
			$('#update-exam').click(function(event){
			event.preventDefault();
			var id = $('#update-exam-hd').val();
			var name  = $('#ename').val();
			var start_day = $('#estart_day').val();
			var duration = $('#eduration').val();
			var note = $('#enote').val();
			var class_id = $('#ename_class').val();
			if($('#form-edit-exam').valid()){
				$.ajax({
					url :"api/update-exam",
					type: "POST",
					data : {id:id,name: name,start_day: start_day,duration: duration,note: note,class_id: class_id},
					dataType:"json",
					success:function(response){ 
						$("#edit-exam").modal("hide");
						$('#list-exam').DataTable().ajax.reload();
						if(response.code == 1){
						toastr.success('Sửa thành công!');
						$('#form-edit-exam').trigger("reset");

					}else{
							toastr.error('Lỗi không thể sửa bản ghi!');
						}
			document.getElementById("form-edit-exam").reset();
					}
					})
				}  
				
  		})          
//set-Point
	$(document).on('click','.button-set-point',function(){
	$('#model-add-setPoint').modal('show');
	var examid = $(this).attr("examid");	
	var aTable =	$('#set-point').DataTable({
		"columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]],
		paging: false,
		searching: false,
		"bDestroy": true,
		"autoWidth": false,

		ajax: {
			url: 'api/get-liststudent',
			data: {examid:examid},
			dataSrc: 'data',
	},
			"columns": [
				{ "data": "id" },
				{ "data": "student_code" },
				{ "data": "name" },
                { "data": function(data, type, full) {
					return "<input min='1' max='10' value='' id='ip-set-point' class='set-point' type='number'>\
					<input type='hidden' name='' value='"+data.exams_id+"' id='get_examid'>"
                }},	
		]
	});
	aTable.on( 'order.dt search.dt', function () {
        aTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
});

// Thêm điểm

$('#setPoint').on('click', function() {
	var student_id = [];
	var point = [];
	var exams_id = [];
	var pointnull = 0;
	
	for($i=0;$i<$('#set-point').DataTable().data().count();$i++){
		student_id.push($('#set-point').DataTable().cell($i,0).data());
		point.push($('#set-point').DataTable().cell($i,3).nodes().to$().find('input').val());
		exams_id.push($('#get_examid').val());
		if($('#set-point').DataTable().cell($i,3).nodes().to$().find('input').val() == ""){
			pointnull++;
		}
	}
	if(pointnull == 0){
		$.ajax({
			url :"api/add-pointexam",
			type: "post",
			data : {student_id:student_id,point: point,exams_id: exams_id},
			dataType:"json",
			success:function(response){ 
	
				 $("#model-add-setPoint").modal("hide");
		//		 $('#model-add-setPoint').DataTable().ajax.reload();
				 if(response.code == 1){
				 toastr.success('Thêm điểm thành công');
				 $('#ip-set-point').prop('readonly', true);
				 }else{
					 toastr.error('Lỗi không thể Thêm!');
				 }
	
			}
			})
	}else{
		toastr.error('Lỗi chưa nhập đủ điểm cho học sinh!');
	}	
	
})




// Xem Điểm
$(document).on('click','.button-get-point',function(){
	$('#model-get-Point').modal('show');
	var examid = $(this).attr("examid");	
	var tablesetpoint =	$('#get-point').DataTable({
		paging: false,
		searching: false,
		"bDestroy": true,
		"autoWidth": false,
		ajax: {
			url: 'api/get-pointexam',
			data: {examid:examid},
			dataSrc: 'data',
	},
			"columns": [
				{ "data": "student_code" },
				{ "data": "student_name" },
				{ "data": function(data, type, full) {
					return "<input min='1' max='10' value='"+data.point+"' id='"+data.student_id+"' class='set-point-update'  type='number'>\
					<input type='hidden' name='' value='"+examid+"' id='get_examid'>"
				}},
				{ "data": function(data, type, full) {
					return 	'<button type="button" student_id="'+data.student_id+'"  class="button-update-point btn btn-info">\
					<i class="fa fa-check-square" aria-hidden="true"></i></button>'
                }},
		]
	});
})
//update điểm

$(document).on('click','.button-update-point',function(){
	var student_id = $(this).attr("student_id");
	var examination_id = $('#get_examid').val();
	var point =$('#'+student_id).val();
	$('#form-add-exam').validate(
		{
			rules : {
			point : {
				required : true,
			},
			},
			messages: {
			point : {
				required : "Không được đế trống",
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
		})
	if($('#form-edit-exam').valid()){

	$.ajax({
		url :"api/update-point",
		type: "POST",
		data : {examination_id: examination_id,student_id: student_id,point: point},
		dataType:"json",
		success:function(response){ 
			$('#get-point').DataTable().ajax.reload();
			if(response.code == 1){
			toastr.success('Sửa thành công!');
		}else{
				toastr.error('Lỗi không thể sửa bản ghi!');
			}
		}
		})
	}

})