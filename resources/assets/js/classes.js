$(function () {
    function updateStatus(){
        $.ajax({
            type : 'get',
            url : 'api/auto-update-status',
            success:function(response){
            }
        })
    }
    window.onload = updateStatus;
    var tableClass = $('#list_class').DataTable({
        "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]],
        ajax: {
            url: 'api/get-list-class',
            dataSrc: 'data',
        },
        columns: [
            {data:null},
            { data: "name" , name:"name"},
            { data: "class_code", name:"class_code" },
            { data: "teacher_name", name:"teacher_name" },
            { data: "class_size", name:"class_size" },
            { data:  "start_date", name:"start_date"},
            { 
                render:function(data, type, row) 
                {
                    var arr_date = (row.schedule).split(",");
                    var days = "";
                    for(var i = 0; i<arr_date.length-1;i++){
                        switch(Number(arr_date[i])){
                            case 0:
                                day = "<div>Chủ nhật</div>";
                                break;
                            case 1:
                                day = "<div>Thứ hai</div>";
                                break;
                            case 2:
                                day = "<div>Thứ ba</div>";
                                break;
                            case 3:
                                day = "<div>Thứ tư</div>";
                                break;
                            case 4:
                                day = "<div>Thứ năm</div>";
                                break;
                            case 5:
                                day = "<div>Thứ sáu</div>";
                               break;
                            case 6:
                                day = "<div>Thứ bảy</div>";
                                break;
                        }
                        days+=day;

                    }
                    return days;
                    
                }
            },
            { data: "time_start" , name:"time_start"},
            {
                "render":function(data, type, row) 
                {
                    switch(row.status){
                        case 0:   

                            return '<select class="change-s" class_id="'+row.id+'" id="'+row.id+'">\
                                        <option value="0" selected>Đang tuyển sinh</option>\
                                        <option value="1">Đang học</option>\
                                        <option value="2">Đã kết thúc</option>\
                                        <option value="3">Tạm dừng</option>\
                                    </select>'+
                                    '<input name="'+row.id+'" type="hidden" class="statuschange" value="'+row.id+'">';
                            break;
                        case 1:
                            return '<select class="change-s" class_id="'+row.id+'" id="'+row.id+'">\
                                        <option value="0" >Đang tuyển sinh</option>\
                                        <option value="1" selected>Đang học</option>\
                                        <option value="2">Đã kết thúc</option>\
                                        <option value="3">Tạm dừng</option>\
                                    </select>'+
                                    '<input name="'+row.id+'" type="hidden" class="statuschange" value="'+row.id+'">';
                            break;
                        case 2:
                            return '<select class="change-s" class_id="'+row.id+'" id="'+row.id+'">\
                                        <option value="0" selected>Đang tuyển sinh</option>\
                                        <option value="1">Đang học</option>\
                                        <option value="2" selected>Đã kết thúc</option>\
                                        <option value="3">Tạm dừng</option>\
                                    </select>'+
                                    '<input name="'+row.id+'" type="hidden" class="statuschange" value="'+row.id+'">';
                            break;
                        case 3:
                            return '<select class="change-s" class_id="'+row.id+'" id="'+row.id+'">\
                                        <option value="0" selected>Đang tuyển sinh</option>\
                                        <option value="1">Đang học</option>\
                                        <option value="2">Đã kết thúc</option>\
                                        <option value="3" selected>Tạm dừng</option>\
                                    </select>'+
                                    '<input name="'+row.id+'" type="hidden" class="statuschange" value="'+row.id+'">';
                            break;
                    }
                    
                }
            },
            {
              "render":function(data, type, row) 
                {
                  
                    return '<button type="button" class_id1="'+row.id+'" class="add-student-class btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>\
                    <button type="button" class_id="'+row.id+'" class="list-student-class btn btn-danger"><i class="fa fa-address-book-o" aria-hidden="true"></i></button>\
                    <button type="button" class_id="'+row.id+'" class="show-timetable btn btn-success"><i title="Xem Thời Khóa Biểu" class="fa fa-book"  aria-hidden="true"></i></button>\
                    <button type="button" class_id="'+row.id+'" title="Xem danh sách kì thi" class=" show-list-exams btn btn-info"><i class="fa fa-graduation-cap" aria-hidden="true"></i> \
                    <button class_id="'+row.id+'" type="button" class="edit-class btn btn-warning"><i title="Sửa thông tin lớp" class="fa fa-pencil-square-o" aria-hidden="true"></i></button>\
                    <button class_id="'+row.id+'" type="button" class=" delete-class1 btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>' 
                    
                }
            }
        ]
     
    });
    tableClass.on( 'order.dt search.dt', function () {
        tableClass.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    $('#form-create-class').validate(
            {
                rules: {
                    "name": {
                        required: true
                    },
                    "class_code": {
                        required: true,
                    },
                    "schedule": {
                        required: true
                    },
                    "time_start": {
                        required: true
                    },
                    "duration": {
                        required: true,
                        number: true,
                    },
                    "class_size": {
                        required: true,
                        number: true,
                    },
                    "start_date": {
                        required: true         
                    }

                },
                messages: {
                    "name": {
                        required: "Tên lớp không được trống!"
                    },
                    "class_code": {
                        required: "Mã lớp học không được trống!",
                    },
                    "schedule": {
                        required: "Bạn chưa chọn các ngày học!"
                    },
                    "class_size": {
                        required: "Sĩ số không được bỏ trống!",
                        number : "Sĩ số phải là kiểu số",
                    },
                    "start_date": {
                        required: "Ngày  bắt đầu không được trống!",
                    },
                    "duration": {
                        required: "Thời lượng không được trống!",
                        number : "Thời lượng là kiểu số!",
                    },
                    "time_start": {
                        required: "Thời gian bắt đầu không được trống!",
                    },
                    "class_size":{
                        required: "Sĩ số không được trống!",
                        number : "Sĩ số là kiểu số!",
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
    $('#form-edit-class').validate(
            {
                rules: {
                    "name_edit": {
                        required: true
                    },
                    "class_code_edit": {
                        required: true,
                    },
                    "class_size_edit": {
                        required: true,
                        number: true,
                    },
                },
                messages: {
                    "name_edit": {
                        required: "Tên lớp không được trống!"
                    },
                    "class_code_edit": {
                        required: "Mã lớp học không được trống!",
                    },
                    "class_size_edit":{
                        required: "Sĩ số không được trống!",
                        number : "Sĩ số là kiểu số!",
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
    $("#button-create-class").click(function(){
        $('#modal-create-class').modal('show');
        $("#name_teacher").empty();
         $.ajax({
            dataType : 'json',
            type : 'get',
            url : 'api/get-name-teacher',
            success:function(response){
                $.each(response.data, function () {
                    $("#name_teacher").append("<option id='course_id' value="+this.id+">"+this.name+"</option>")
                });
            }
        });
        $("#name_course").empty();
        $.ajax({
            dataType : 'json',
            type : 'get',
            url : 'api/get-name-course',
            success:function(response){
                $.each(response.data, function () {
                    $("#name_course").append("<option id='teacher_id' value="+this.id+">"+this.name+"</option>")
                });
            }
        })
    });

    jQuery.datetimepicker.setLocale('vi');

    $('#start_date').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        minDate: '-1970-01-1',
    });
    $('#start_date').blur(function(){
        let a = $(this).val();
        let x = new Date(a)
        let thu = x.getDay();
        $(":checkbox[value="+thu+"]").prop("checked","true");
        $(":checkbox").not(":checkbox[value="+thu+"]").prop('checked', false);
        $(":checkbox[value="+thu+"]").prop("disabled","true");
        $(":checkbox").not(":checkbox[value="+thu+"]").prop('disabled', false);
    });

    $("#create-class").click(function(event){
        event.preventDefault();
        var name        = $('#name').val();"<br />"
        var class_code  = $('#class_code').val();
        var start_date  = $('#start_date').val();
        var duration    = $('#duration').val();
        var time_start  = $('#time_start').val();
        var teacher_id  = $('#teacher_id').val();
        var course_id   = $('#course_id').val();
        var class_size  = $('#class_size').val();
        var val = [];
        var schedule = "";
        $(':checkbox:checked').each(function(i){
            val[i] = $(this).val();
            schedule =schedule+val[i]+",";
        });

        if($('#form-create-class').valid()){
            
            $.ajax({
                url: "api/create-class",
                method:"POST",
                data:
                {
                    name:name,
                    class_code:class_code,
                    time_start:time_start,
                    teacher_id:teacher_id,
                    duration :duration,
                    course_id:course_id,
                    schedule:schedule,
                    start_date:start_date,
                    class_size:class_size
                },
                success:function(response){
                    if(response.code==0){
                        toastr.error(response.message);
                    }else{
                        $('#form-create-class')[0].reset();
                        $("#modal-create-class").modal("hide");
                        toastr.success('Thêm lớp thành công!');
                        $('#list_class').DataTable().ajax.reload();
                    }
                }
            });
        }
    });
    
    $(document).on('click','.delete-class1',function(){
        var id = $(this).attr('class_id');
        if(id){
                swal({
                  title: "Bạn có muốn?",
                  text: "xóa lớp học này?",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willDelete) => {
                  if (willDelete) {
                         $.ajax({
                            url: "api/delete-class",
                            method:"GET",
                            data:{id:id},
                            success:function(response){
                                if(response.code==1){
                                    $('#list_class').DataTable().ajax.reload();
                                    toastr.success(response.message);
                                    
                                }else
                                    toastr.error(response.message);
                            }
                        });
                  }else {
                    tableClass.ajax.reload();
                  }
                });
             }else{
                toastr.warning('Không tìm thấy lớp học cần xóa!');
             }
    });

    $(document).on('click','.edit-class',function(){
        $('#modal-edit-class').modal('show');
        var class_id = $(this).attr('class_id');
          $.ajax({
            dataType : 'json',
            type:'get',
            url : 'api/edit-class',
            data : {id:class_id},
            success: function(response){
                    $('#class_code_edit').val(response['class_code']);
                    $('#name_edit').val(response['name']);
                    $('#start_date_edit').val(response['start_date']);
                    $('#time_start_edit').val(response['time_start']);
                    $('#duration_edit').val(response['duration']);
                    $('#class_size_edit').val(response['class_size']);
                    $('.button-edit-class').attr('data-id',response['id']);
            }
        });
        
    });

    $(".button-edit-class").click(function(){
        var id = $(this).attr('data-id');
        var class_code   =  $('#class_code_edit').val();
        var name  =  $('#name_edit').val();
        var class_size   =   $('#class_size_edit').val();
        var data = {id:id,class_code:class_code,name:name,class_size:class_size};
           if($("#form-edit-class").valid()){
                $.ajax({
                    type : 'post',
                    url : 'api/edit-class',
                    data : data,
                    success: function(response){
                        if(response.code==1){
                            $("#modal-edit-class").modal("hide");
                            $('#list_class').DataTable().ajax.reload();
                            toastr.success(response.message);
                        }else{
                            toastr.error(response.message);
                        }
                    }
                });
            }
     });

    $(document).on('click','.add-student-class',function(){
        $('#modal-add-student-class').modal('show');
        var class_id = $(this).attr('class_id1');
        $('#get_class_id12').val(class_id);
        var tableStudent = $('#table-student-class').DataTable({
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": [[ 1, 'asc' ]],
            paging: false,
            "bDestroy": true,
            ajax: {
                url: 'api/get-student-not-in-class',
                data: {class_id:class_id},
                dataSrc: 'data',
            },
            columns :[
                {data:null},
                {data:"name",name:'name'},
                {data:"address",name:'address'},
                {data:"mobile",name:'mobile'},
                {data:"birthday",name:'birthday'},
                {
                    render:function(data, type, row)
                    {
                        if(row.gender==0){
                            return "Nam";
                        }else if(row.gender==1)
                            return "Nữ";
                        else
                            return "Khác";
                    }
                },
                {
                    "data":function(data, type, full)
                    {
                        return ' <button type="button" student_id1="'+data.id+'" class="button-add-student btn btn-success">Thêm vào lớp</button>'
                    }
                },
            ]
        });
        tableStudent.on( 'order.dt search.dt', function () {
            tableStudent.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            });
        } ).draw();
    });

    $(document).on('click','.button-add-student',function(){
        var student_id = $(this).attr('student_id1');
        var class_id = $('#get_class_id12').val();
        $.ajax({
            url : "api/add-student-to-class",
            type: "post",
            data : {class_id:class_id,student_id:student_id},
            success:function(response){
                if(response.code == 0){
                    $('#table-student-class').DataTable().ajax.reload();
                    toastr.error(response.message);
                }else{
                    $('#table-student-class').DataTable().ajax.reload();
                    toastr.success(response.message);
                }
            }
        });
    });
   
    $(document).on('click','.list-student-class',function(){
        $('#modal-list-student-class').modal('show');
        var class_id = $(this).attr('class_id');
        var tableStudentClass = $('#table-student-of-class1').DataTable({
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": [[ 1, 'asc' ]],
            paging: false,
            "bDestroy": true,
            ajax: {
                url: 'api/get-list-class-student',
                data: {class_id:class_id},
                DataSrc: 'data',
            },
            columns: [
                {data:null},
                {data:"name",name:'name'},
                {data:"address",name:'address'},
                {data:"mobile",name:'mobile'},
                {data:"birthday",name:'birthday'},
                {
                    render:function(data, type, row)
                    {
                            if(row.gender==0){
                                return "Nam";
                            }else if(row.gender==1)
                                return "Nữ";
                            else
                                return "Khác";
                    }
                },
                {
                    render:function(data, type, row)
                    {
                        return ' <button type="button" student_id="'+row.id+'" class="button-delete-student btn btn-danger">Xóa khỏi lớp</button>'
                    }
                },
            ]
        });
        tableStudentClass.on( 'order.dt search.dt', function () {
            tableStudentClass.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });

    $(document).on('click','.button-delete-student',function(){
        var student_id = $(this).attr('student_id');
            swal({
              title: "Bạn có muốn?",
              text: "xóa học sinh này?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                     $.ajax({
                        url: "api/delete-student-class",
                        method:"GET",
                        data:{id:student_id},
                        success:function(response){
                            if(response.code==1){
                                $('#table-student-of-class1').DataTable().ajax.reload();
                                toastr.success(response.message);
                            }else
                                 toastr.error(response.message);
                        }
                    });
              }
            });
         
    });
    $(document).on('click','.show-timetable',function(){
        var class_id = $(this).attr('class_id');
        window.location.href= asset+ "timetable?classid="+class_id+""
    });
    $(document).on('click','.show-list-exams',function(){
        var class_id = $(this).attr('class_id');
        window.location.href= asset+ "exam?classid="+class_id+""
    });

    // function ChangeStatus(){
    //     var cars = [];
    //     $(".statuschange").each(function() {
    //         cars.push($(this).val());

    //     });
    //     for(var i = 0; i <= cars.length; i++){
    //         let car = cars[i];
    //         $(document).on('change','#status_class'+car+'',function(e){
    //             e.preventDefault();
    //             var id = $(this).attr('class_id');
    //             let status = $('select[id=status_class'+car+']').val()
    //             $.ajax({
    //                 type: 'post',
    //                 url: "api/update-status-class",
    //                 data: {id:id,status:status},
    //                 success: function(response){
    //                     toastr.success('Cập nhật thành công');
    //                 }
    //             })
    //         })
    //     }
    // }

    // window.onload = ChangeStatus;
   
   $(document).on('change','.change-s',function(e){
        e.preventDefault();
        var id = $(this).attr('class_id');
        let status = $('select[id='+id+']').val()
        $.ajax({
            type: 'post',
            url: "api/update-status-class",
            data: {id:id,status:status},
            success: function(response){
                toastr.success('Cập nhật thành công');
            }
        })
    })
});
