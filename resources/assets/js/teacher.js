$(function () {
     // $('.text-danger').css('display', 'none');
     $('#list-teacher').DataTable();
     $('.btn-danger').click(function(){
        swal({
            title: "Bạn có chắc muốn xóa?",
            text: "Bạn sẽ không thể khôi phục lại bản ghi này!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                alert('ok')
            }else{
                toastr.warning('Bạn đã hủy!');
            }
        });
    })
    // $('.text-danger').css('display', 'none');
    $('.btn-success').click(function(event) {
        /* Act on the event */
        var name = $("#name").val();
        var email = $("#email").val();
        var address= $("#address").val();
        var mobile = $("#mobile").val();
        var birthday = $("#birthday").val();
        var exp = $("#exp").val();
        var certificate = $("#certificate").val();
        var description = $("#description").val();
        if(name == ""){
            $('#err-name').css('display','block');
        }else{
            $('#err-name').css('display','none');
        }
        if(email == ""){
            $('#err-email').css('display','block');
        }else{
            $('#err-email').css('display','none');
        }
        if(address == ""){
            $('#err-address').css('display','block');
        }else{
            $('#err-address').css('display','none');
        }
        if(mobile == ""){
            $('#err-mobile').css('display','block');
        }else{
            $('#err-mobile').css('display','none');
        }
        if(birthday == ""){
            $('#err-birthday').css('display','block');
        }else{
            $('#err-birthday').css('display','none');
        }
        if(exp == ""){
            $('#err-exp').css('display','block');
        }else{
            $('#err-exp').css('display','none');
        }
        if(certificate == ""){
            $('#err-certifidate').css('display','block');
        }else{
            $('#err-certifidate').css('display','none');
        }
        if(description == ""){
            $('#err-description_1').css('display','block');
        }else{
            $('#err-description_1').css('display','none');
        }
    })
    /**
     * add teacher
     */
     $('#addTeacher').click(function(event) {
        /1* Act on the event */
        var name = $('#name').val();
        var email = $('#email').val();
        var address = $('#address').val();
        var mobile = $('#mobile').val();
        var birthDate = $('#birthday').val();
        var gender = $('#gender').val();
        var exp = $('#exp_1').val();
        var certificate = $('#certificate').val();
        var description = $('#err-description').val();
        if ( name != "" && email != "" && address !="" 
            && mobile != "" && birthDate != "" && gender != ""
            && exp !="" && certificate !="" && certificate !="") {
            var formData = new FormData();
        formData.append("name", name);
        formData.append("email", email);
        formData.append("address", address);
        formData.append("mobile", mobile);
        formData.append("birthDate", birthDate);
        formData.append("gender", gender);
        formData.append("exp", exp);
        formData.append("certificate", certificate);
        formData.append('description', description);
        $.ajax({
            url: 'api/add-teacher',
            type: 'POST',
            contentType: false,
            processData: false,
            data: formData,
            success: function(response) {
                alert(response.message);
                location.reload();
            },
            error: function(response) {
                alert("error");
            }
        })
    }
});

    /**
     * get list teacher
     */
     $('#list-teacher-1').DataTable( {
        "ajax": 'api/list-teachers',
        "responsive": true,
        "columns": [
        { "data": null },
        { "data": "name" },
        { "data": "address" },
        { "data": "mobile"},
        { "data": "birthdate" },
        { "data": "nationality" },
        { "data": "description" },
        { "data": null }
        ],
        "columnDefs": [ ],
        "order": [[ 1, 'asc' ]],
        "columnDefs": [ {
            // "order": [[ 1, 'asc' ]],
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ,{
            "targets": -1,
            "data": null,
            "defaultContent": `
            <a title="sửa thông tin" href="${asset}/teacher-edit" class="btn btn-warning edit-teacher" id="edit-go" 
            onclick="{
                var table = $('#list-teacher-1').DataTable();
                var data = table.row($(this).closest('tr')).data();
                localStorage.setItem('id', data.id);
            }">
            <i class="fa fa-pencil" aria-hidden="true"></i>
            </a>
            <button title="xóa giao vien" class="btn btn-danger" onclick="{
                var table = $('#list-teacher-1').DataTable();
                var id = table.row($(this).closest('tr')).data().id;
                _delete(id);
            }" id="delete"><i class="fa fa-trash" aria-hidden="true"></i></button>
            `
        } ]
    });
     var t = $('#list-teacher-1').DataTable()
     t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
 });
 /**
     *  edit teacher
     */
     $('#edit-teacher').click(function(event) {
        /* Act on the event */
        var id  = localStorage.getItem('id');
        var name  = $('#name').val();
        var address  = $('#address').val();
        var mobile = $('#mobile').val();
        var birthDate  = $('#birthday').val();
        var exp = $('#exp').val();
        var certifycate = $('#certifycate').val();
        var description  = $('#err-description').val();
        if (name != "" && address !="" && mobile !="" && birthDate
            && exp != "" && certifycate !="" && description !=""  && id !=""
            ) {
            var formData = new FormData();
        formData.append('id', id)
        formData.append("name", name);
        formData.append("address", address);
        formData.append("mobile", mobile);
        formData.append("birthDate", birthDate);
        formData.append("exp", exp);
        formData.append("certifycate", certifycate);
        formData.append("description", description);
        $.ajax({
            url: 'api/edit-teacher',
            type: "POST",
            contentType: false, 
            processData: false,
            data: formData,
            success: function(response){
                alert(response.message);
                location.reload();
            },
            error: function(){
                alert("can not edit teacher info !!");
            }
        });
    }
});
     window._delete=function(id){
        if (id !="") {
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
                            alert(response.message);
                            location.reload();
                        },
                        error: function (response) {
                            toastr.warning("can not delete teacher !!");
                        }
                    });
                }
                else{
                    toastr.warning('Bạn đã hủy!');
                }
            })
        }
    }