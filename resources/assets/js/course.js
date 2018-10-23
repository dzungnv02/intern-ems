$(function () {
    var tableCourse = $('#list-course').DataTable({
        "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]],
        "ajax":"api/get-list-course",
      
        "columns": [
            {"data":"id"},
            { "data": "code" },
            { "data": "name" },
            { 
                "data":function(data, type, full) 
                {
                    if(data.level==0){
                        return "Cơ bản";
                    }else
                        return "Nâng cao";
                }
            },
            { "data": "curriculum" },
            { "data": "duration" },
            { "data": "fee" },
            {
              "data":function(data, type, full) 
              {
                return '<button type="button" class="edit-course btn btn-warning" course_id="'+data.id+'"><i class="fa fa-pencil-square" aria-hidden="true"></i></button> \
                <button course_id="'+data.id+'" type="button"   class="delete-course btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>' 
              }
            }
        ]
    });
    tableCourse.on( 'order.dt search.dt', function () {
            tableCourse.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
    } ).draw();

    $('#form-edit-course').validate(
            {
                rules: {
                    "name": {
                        required: true
                    },
                    "code": {
                        required: true
                    },
                    "curriculum": {
                        required: true
                    },
                    "duration": {
                        required: true
                    },
                    "fee": {
                        required: true,
                        number: true,
                    }
                },
                messages: {
                    "name": {
                        required: "Bắt buộc nhập tên khóa học"
                    },
                    "code": {
                        required: "Bắt buộc nhập mã khóa học"
                    },
                    "curriculum": {
                        required: "Bắt buộc nhập giáo trình"
                    },
                    "duration": {
                        required: "Bắt buộc nhập thời gian"
                    },
                    "fee": {
                        required: "Bắt buộc nhập học phí",
                        number: "Bắt buộc nhập số"
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
    );
    $('#form-create-course').validate(
            {
                rules: {
                    "name": {
                        required: true
                    },
                    "code": {
                        required: true
                    },
                    "curriculum": {
                        required: true
                    },
                    "duration": {
                        required: true,
                        number: true,
                    },
                    "fee": {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    "name": {
                        required: "Bắt buộc nhập tên khóa học"
                    },
                    "code": {
                        required: "Bắt buộc nhập mã khóa học"
                    },
                    "curriculum": {
                        required: "Bắt buộc nhập giáo trình"
                    },
                    "duration": {
                        required: "Bắt buộc nhập thời gian",
                        number: "Bắt buộc nhập số"
                    },
                    "fee": {
                        required: "Bắt buộc nhập học phí",
                        number: "Bắt buộc nhập số"
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
    );
    $('#button-create-course').click(function(){
        $('#modal-create-course').modal('show');
    });
    $("#create-course").click(function(event){
        event.preventDefault();
        var name        = $('#name').val();
        var code        = $('#code').val();
        var duration    = $('#duration').val();
        var fee         = $('#fee').val();
        var curriculum  = $('#curriculum').val();
        var level       = $('#level').val();
        var data        = {name:name,code:code,duration:duration,fee:fee,curriculum:curriculum,level:level};
        if($('#form-create-course').valid()){
                    $.ajax({
                        url: "api/create-course",
                        method:"POST",
                        data:data,
                        success:function(response){
                            if(response.code==1)
                            {
                                tableCourse.ajax.reload();
                                toastr.success(response.message);
                                $('#form-create-course')[0].reset();
                                $('#modal-create-course').modal('hide');
                            }else
                                toastr.error(response.message);
                            
                        }
                    });
        }
    });
    //Form Edit
    $(document).on('click','.edit-course',function(){
        $('#modal-edit-course').modal('show');
        var course_id = $(this).attr('course_id');
        $('#get_course_id1').val(course_id);
        $.ajax({
            dataType : 'json',
            type:'get',
            url : 'api/edit-course',
            data : {course_id:course_id},
            success: function(response){
                    $('#code_edit').val(response['code']);
                    $('#name_edit').val(response['name']);
                    $('#curriculum_edit').val(response['curriculum']);
                    $('#level_edit').val(response['level']);
                    $('#duration_edit').val(response['duration']);
                    $('#fee_edit').val(response['fee']);
            }
        });
        
    });

    $('.button-edit-course').click(function(){
        var course_id = $('#get_course_id1').val();
        var name        = $('#name_edit').val();
        var code        = $('#code_edit').val();
        var duration    = $('#duration_edit').val();
        var fee         = $('#fee_edit').val();
        var curriculum  = $('#curriculum_edit').val();
        var level       = $('#level_edit').val();
        var data        = {
                            course_id:course_id,
                            name:name,code:code,
                            duration:duration,
                            fee:fee,
                            curriculum:curriculum,
                            level:level
                        };
        if($('#form-edit-course').valid()){
                $.ajax({
                    dataType : 'json',
                    type : 'post',
                    url : 'api/edit-course',
                    data : data,
                    success: function(response){
                        if(response.code==1)
                        {
                            $("#modal-edit-course").modal("hide");
                            tableCourse.ajax.reload();
                            toastr.success(response.message);
                        }else{
                            toastr.error(response.message);
                        }   
                    }
                });
            }
    });

    $(document).on('click','.delete-course',function(){
         var course_id = $(this).attr('course_id');
         swal({
              title: "Bạn có muốn?",
              text: "xóa khóa học này?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
         .then((willDelete) => {
              if (willDelete) {
                     $.ajax({
                        url: "api/delete-course",
                        method:"GET",
                        data:{id:course_id},
                        success:function(response){
                            if(response.code==1){
                                toastr.success(response.message);
                                tableCourse.ajax.reload();
                            }else
                                toastr.error(response.message);
                        }
                    });
              }else {
              }
            });
    });
});