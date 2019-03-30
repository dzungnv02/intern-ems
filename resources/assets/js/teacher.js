$(function () {

    var table_teachers = $('TABLE#teacher-list');
    var table = null;
    var modal_teacher_schedule = $('DIV#modal-teacher-schedule');
    var modal_add_schedule = $('DIV#modal-teacher-schedule-add');
    var table_schedule = null;

    var table_act_buttons = '<button type="button" title="Sửa thông tin giáo viên" class="edit btn btn-sm btn-warning"><i title="Sửa thông tin giáo viên" class="fa fa-pencil-square-o" aria-hidden="true"></i></button>\
    <button type="button" title="Xoá giáo viên" class="delete btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>\
    <button type="button" title="Lịch" class="schedule btn btn-sm btn-info"><i class="fa fa-calendar-times-o" aria-hidden="true"></i></button>';


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
                    console.log(mode);
                    table.clear();
                    table.rows.add(list);
                    table.draw();
                }
            },
        });
    }

    var renderTeacherList = (list) => {
        table = $('TABLE#teacher-list').DataTable({
            data: list,
            language: datatable_language,
            columns: [{data: null},
                {data: 'name'},
                {
                    data: 'email',
                    render: (data, type, row) => {
                        return data != null ? '<a href="mailto:' + data + '">' + data + '</a>' : '';
                    }
                },
                {data: 'mobile'},
                {data: 'address'},
                {data: 'nationality'},
                {data: null}
            ],
            "columnDefs": [{
                "targets": -1,
                "data": null,
                "defaultContent": table_act_buttons
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

        table.on('click', 'button.delete', function () {
            var data = table.row($(this).parents('tr')).data();
            modalConfirm((confirm) => {
                if (confirm) {
                    del(data.id);
                }
            }, 'Bạn có muốn xoá giáo viên <strong>' + data.name + '</strong> không?')
        });

        table.on('click', 'button.edit', function () {
            var data = table.row($(this).parents('tr')).data();
            $('FORM#frmTeacher INPUT#id').val(data.id);
            showTeacherModal('edit');
        });

        table.on('click', 'button.schedule', function () {
            var data = table.row($(this).parents('tr')).data();
            $(modal_teacher_schedule).find('.modal-title').html('Lịch của giáo viên <strong>'+data.name+'</strong>');

            get_teacher_schedule(data.id, () => {
                $(modal_teacher_schedule).find('FORM#frmTeacher INPUT#teacher_id').val(data.id);
                $(modal_teacher_schedule).modal('show');
            });
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

    var del = (id) => {
        var url = '/api/delete-teacher'
        $.ajax(url, {
            method: 'POST',
            dataType: 'json',
            data: {
                "id": id
            },
            success: function (response) {
                getTeacherList('reload');
            },
        });
    };

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

    var get_teacher_schedule = (teacher_id, callback) => {
        var data = {
            id: teacher_id
        };
        $.ajax({
            url: 'api/get-teacher-schedule',
            method: 'GET',
            data: data,
            success: (response) => {
                render_teacher_schedule(response.data, callback);
            }
        });
    };

    var render_teacher_schedule = (data, calllback) => {
        if ($.fn.dataTable.isDataTable('DIV#modal-teacher-schedule TABLE#table-schedule')) {
            table_schedule = $('DIV#modal-teacher-schedule TABLE#table-schedule').DataTable();
            table_schedule.clear();
            table_schedule.rows.add(data);
            table_schedule.draw();
        } else {
            table_schedule = $('DIV#modal-teacher-schedule TABLE#table-schedule').DataTable({
                data: data,
                language: datatable_language,
                'autoWidth': false,
                "ordering": false,
                "searching": false,
                "bPaginate": true,
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false
                }],
                columns: [
                    {data: null},
                    {
                        data: null,
                        render: (data, type, row) => {
                            return 'Từ: ' + row.start_time + '<br />' + 'Đến: '+ row.end_time;
                        }
                    },
                    {
                        data: 'appoinment_type',
                        render: (data, type, row) => {
                            return schedule_type[data];
                        }
                    },
                    {
                        data: 'desc'
                    }
                ]
            });
            table_schedule.on('draw.dt order.dt search.dt', function () {
                table_schedule.column(0, {}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        }
        if (calllback != undefined) calllback();
    }

    var show_add_schedule_modal = () => {
        if ($(modal_teacher_schedule).is(':visible')) {
            var teacher_id = $(modal_teacher_schedule).find('FORM#frmTeacher INPUT#teacher_id').val();
            $(modal_teacher_schedule).removeClass("fade").modal("hide");
            $(modal_add_schedule).find('FORM#frmTeacherSchedule INPUT#teacher_id').val(teacher_id);
            $(modal_add_schedule).modal("show").addClass("fade");
        }
    }

    $(modal_teacher_schedule).find('BUTTON#btnAddSchedule').on('click', (e) =>{
        show_add_schedule_modal();
    });

    var schedule_type_select = (selected) => {
       var appoinment_type = $(modal_add_schedule).find('SELECT#appoinment_type');
       $(appoinment_type).empty();
       for (var i in schedule_type) {
           if (i == 1) continue;
            var opt = $('<option></option>', {
                value: i,
                text: schedule_type[i]
            });
            if (selected == i) opt.prop('selected', 'selected');
            appoinment_type.append(opt);
       }
       if (selected == undefined)
            $($(appoinment_type).find('OPTION')[0]).attr('selected', 'selected');
    }

    var get_class_list = (selected) => {
        var class_id = $('SELECT.select2[id="class_id"]');
        $(class_id).empty();
        $(class_id).append($('<option></option>', {value: '0', text: '[Chọn lớp]'}));
        $.ajax('api/get-list-class', {
            type: 'GET',
            contentType: 'application/json',
            success: function (response) {
                if (response.code == 1) {
                    if (response.data.length > 0) {
                        for (var i = 0; i < response.data.length; i++) {
                            var cls = response.data[i];
                            var opt = $('<option></option>', {value: cls.id, text: cls.name});
                            if (selected == cls.id) opt.prop('selected', 'selected');
                            $(class_id).append(opt);
                        }
                        if (selected == undefined)
                            $($(class_id).find('OPTION')[0]).attr('selected', 'selected');
                    }
                }
                
            }
        });
    }

    var get_student_list = (callback) => {
        $(student_id).empty();
        $.ajax('/invoice/student-list', {
            type: 'GET',
            contentType: 'application/json',
            success: function (response) {
                var student_target = $(modal_add_schedule).find('SELECT#student_id');
                if (response.code == 0) {
                    var first_val = null;
                    if (response.data.list.length > 0) {
                        for (var i = 0; i < response.data.list.length; i++) {
                            var student = response.data.list[i];
                            
                            first_val = (first_val == null) ? student.id : first_val;
                                                        
                            var display_text = (student.student_code ? 'Code: <span class="text-bold">' + student.student_code + '</span> - ' : 'NO CODE - ') + student.name;
                            var opt = $('<option></option>', {
                                value: student.id,
                                html: display_text
                            });
                            $(student_target).append(opt);
                        }
                        if (callback != undefined) {
                            callback();
                        }
                    }
                    $(modal_add_schedule).find('SELECT#appoinment_type').change();
                }
            }
        });
    }

    var add_teacher_schedule = (callback) => {
        var data = {};
        var form = $(modal_add_schedule).find('FORM#frmTeacherSchedule');
        var inputs = $(form).find('input,select,textarea');
        $(inputs).each((index, el) => {
            console.log('el',el);
            data[el.id] = $(el).val();
        });

        $.ajax({
            url: 'api/add_teacher_schedule',
            method: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: (response) => {
                console.log(response);
                var teacher_id = $(form).find('INPUT#teacher_id').val();
                
                get_teacher_schedule(teacher_id, () => {
                    if ($(modal_add_schedule).is(':visible')) {
                        $(modal_add_schedule).removeClass("fade").modal("hide");
                        $(modal_teacher_schedule).modal("show").addClass("fade");
                    }
                });
            }
        });
    };

    if ($(modal_add_schedule).length > 0) {
        $(modal_add_schedule).find('SELECT#appoinment_type').on('change', (e) => {
            var selected_type = $(e.target).children("option:selected").val();
            if (selected_type == 2) {
                $('DIV#related_class').addClass('hidden');
                $('DIV#related_student').removeClass('hidden');
            }
            else {
                $('DIV#related_student').addClass('hidden');
                $('DIV#related_class').removeClass('hidden');
            }
        });
    }

    if (table_teachers.length > 0) {
        getTeacherList('load');
        $('#btnOpenModalTeacher').on('click', (e) => {
            showTeacherModal('new');
            e.preventDefault();
        });

        var modalConfirm = function (callback, message) {
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

        $(modal_add_schedule).on('show.bs.modal', (e) => {
            schedule_type_select();
            get_class_list();
            get_student_list();
        })
    }

    if ($(modal_add_schedule).find('FORM#frmTeacherSchedule').length > 0) {
        $(modal_add_schedule).find('DIV.modal-footer BUTTON#btnSave').on('click', (e) => {
            add_teacher_schedule();
        }) 
    }

});