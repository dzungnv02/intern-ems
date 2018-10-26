$(function () {

    var table_teachers = $('TABLE#teacher-list');
    var table = null;

    var getTeacherList = (mode) => {
        var url = '/api/list-teachers';
        $.ajax(url, {
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                var list = response.data;
                if (mode == 'load') {
                    renderTeacherList(list);
                } else if (mode == 'reload') {
                    table.ajax.data(list);
                    table.draw();
                }
            },
        });
    }

    var renderTeacherList = (list) => {
        table = $('TABLE#teacher-list').DataTable({
            data: list,
            language: {
                "paginate": {
                  "previous": "Trước",
                  "next": "Sau",
                  "first": "Đầu tiên",
                  "last": "Cuối cùng"
                },
                "emptyTable" : "Không có bản ghi nào!",
                "info" : "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ bản ghi",
                "infoEmpty": "Hiển thị 0 bản ghi",
                "search": "Tìm kiếm:",
                "zeroRecords": "Không tìm thấy bản ghi nào phù hợp!",
                "lengthMenu":     "Hiển thị _MENU_ bản ghi"
            },
            columns: [{
                    data: null
                },
                {
                    data: 'name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'mobile'
                },
                {
                    data: 'address'
                },
                {
                    data: 'nationality'
                },
                {
                    data: null
                }
            ],
            "columnDefs": [{
                "targets": -1,
                "data": null,
                "defaultContent": '<a class="edit">Sửa</a>&nbsp;&nbsp;<a class="delete">Xoá</a>'
            }]
        });

        table.on('order.dt search.dt', function () {
            table.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();

        table.on('click', 'a.delete', function () {
            var data = table.row($(this).parents('tr')).data();
            alert(data.name + " is deleted!");
        });

        table.on('click', 'a.edit', function () {
            var data = table.row($(this).parents('tr')).data();
            $('FORM#frmTeacher INPUT#id').val(data.id);
            showTeacherModal('edit');
        });
    };

    var getCountries = () => {
        var nationality = $('SELECT.select2[id="nationality"]');
        $.ajax('/api/countries', {
            type: 'GET',
            contentType: 'application/json',
            success: function (response) {
                if (response.code == 1) {
                    if (response.data != undefined) {

                        for (var n in response.data) {
                            var national = response.data[n];
                            var opt = $('<option></option>', {
                                value: national.citizenship,
                                text: national.citizenship
                            });
                            $(nationality).append(opt);
                        }

                        $($(nationality).find('OPTION')[0]).attr('selected', 'selected');
                    }
                }

            }
        });
    };

    var getTeacher = (id, callback) => {
        var url = '/api/get-teacher/?id=' + id;
        $.ajax(url, {
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.code == 1) {
                    var teacher = response.data[0];
                    console.log(teacher);
                    $('DIV#modal-teacher-form FORM INPUT#crm_id').val(teacher.crm_id);
                    $('DIV#modal-teacher-form FORM INPUT#name').val(teacher.name);
                    $('DIV#modal-teacher-form FORM SELECT#nationality').val(teacher.nationality).change();
                    $('DIV#modal-teacher-form FORM INPUT#email').val(teacher.email);
                    $('DIV#modal-teacher-form FORM INPUT#address').val(teacher.address);
                    $('DIV#modal-teacher-form FORM INPUT#mobile').val(teacher.mobile);
                    $('DIV#modal-teacher-form FORM INPUT#birthdate').val(teacher.birthdate);
                    $('DIV#modal-teacher-form FORM SELECT#gender').val(teacher.gender).change();
                    $('DIV#modal-teacher-form FORM INPUT#experience').val(teacher.experience);
                    $('DIV#modal-teacher-form FORM INPUT#certificate').val(teacher.certificate);
                    $('DIV#modal-teacher-form FORM TEXTAREA#description').html(teacher.description);
                    callback();
                }
            },
        });
    }

    var save = (mode) => {
        var data = {
            'id': $('FORM#frmTeacher INPUT#id').val().trim(),
            'crm_id': $('FORM#frmTeacher INPUT#crm_id').val().trim(),
            'name': $('FORM#frmTeacher INPUT#name').val().trim(),
            'nationality': $('FORM#frmTeacher SELECT#nationality').val().trim(),
            'email': $('FORM#frmTeacher INPUT#email').val().trim(),
            'address': $('FORM#frmTeacher INPUT#address').val().trim(),
            'mobile': $('FORM#frmTeacher INPUT#mobile').val().trim(),
            'birthdate': $('FORM#frmTeacher INPUT#birthdate').val().trim(),
            'gender': $('FORM#frmTeacher SELECT#gender').val().trim(),
            'experience': $('FORM#frmTeacher INPUT#experience').val().trim(),
            'certificate': $('FORM#frmTeacher INPUT#certificate').val().trim(),
            'description': $('FORM#frmTeacher TEXTAREA#description').val().trim(),
        };

        var url = mode == 'new' ? '/api/add-teacher' : '/api/edit-teacher';

        $.ajax(url, {
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (response) {
                if (response.code == 1) {
                    $('DIV#modal-teacher-form').modal('hide');
                    getTeacherList('reload');
                }

            }
        });

    }

    var showTeacherModal = (mode) => {

        if (mode == 'new') {
            $('DIV#modal-teacher-form DIV.modal-header H2.modal-title').text('Thêm giáo viên');
            $('DIV#modal-teacher-form FORM')[0].reset();
            $('DIV#modal-teacher-form').modal('show');
        } else if (mode == 'edit') {
            $('DIV#modal-teacher-form DIV.modal-header H2.modal-title').text('Thay đổi thông tin giáo viên');
            getTeacher($('FORM#frmTeacher INPUT#id').val().trim(), () => {
                $('DIV#modal-teacher-form').modal('show');
            });
        }
    };

    if (table_teachers.length > 0) {
        getTeacherList('load');

        $('#btnOpenModalTeacher').on('click', (e) => {
            showTeacherModal('new');
            e.preventDefault();
        });

        $('#modal-teacher-form').on('show.bs.modal', () => {

        });
    }

    if ($('FORM#frmTeacher').length > 0) {
        getCountries();

        $('FORM#frmTeacher input#birthdate').datetimepicker({
            format: 'Y-m-d'
        });

        $('DIV#modal-teacher-form DIV.modal-footer BUTTON#btnSave').on('click', (e) => {
            var mode = $('FORM#frmTeacher INPUT#id').val().trim() !== '' ? 'edit' : 'new';
            save(mode);
        });
    }

});