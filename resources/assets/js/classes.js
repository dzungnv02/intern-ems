$(document).on({
    ajaxStart: () => {
        $('div.modal#modal-class').find('div.modal-footer > button#create-class').button('loading');
        $('div.modal#modal-class').find('div.modal-footer > button#create-class').prop('disabled', true);
        $('DIV#modal-class FORM#form-class').closest('.form-group').prop('disabled', true);
    },
    ajaxStop: () => {
        $('div.modal#modal-class').find('div.modal-footer > button#create-class').prop('disabled', false);
        $('div.modal#modal-class').find('div.modal-footer > button#create-class').button('reset');
        $('DIV#modal-class FORM#form-class').closest('.form-group').prop('disabled', false);
    }
});

$(function () {
    if ($('TABLE#list_class').length > 0) {
        var form = $('DIV#modal-class FORM#form-class');

        var table_act_buttons = '<button type="button" class="list-student-class btn btn-sm btn-info"><i class="fa fa-address-book-o" aria-hidden="true"></i></button>\
                            <button type="button" class="edit-class btn btn-sm btn-warning"><i title="Sửa thông tin lớp" class="fa fa-pencil-square-o" aria-hidden="true"></i></button>\
                            <button type="button" class="delete-class btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';

        /* 
        <button type="button" class="show-timetable btn btn-sm btn-success"><i title="Xem Thời Khóa Biểu" class="fa fa-book" aria-hidden="true"></i></button>\
        <button type="button" title="Xem danh sách kì thi" class="show-list-exams btn btn-sm btn-info"><i class="fa fa-graduation-cap" aria-hidden="true"></i></button>\
        */

        $('SELECT.select2[id="teacher_id"]').select2({
            placeholder: "Chọn giáo viên",
        });

        var create_class_validate_rules = {
            class_name: {
                required: true
            },
            max_seat: {
                required: true,
                number: true,
                min: 1
            },
            start_date: {
                required: true,
                date: true
            }
        }

        var create_class_validate_messages = {
            class_name: {
                required: "Tên lớp không được trống!"
            },
            max_seat: {
                required: "Số học sinh tối đa không được trống!",
                number: "Hãy nhập giá trị là số lớn hơn 0!",
                min: "Hãy nhập giá trị là số lớn hơn 0!"
            },
            start_date: {
                required: 'Ngày bắt đầu không được trống!',
                date: 'Hãy nhập giá trị là một ngày!'
            }
        }

        $(form).find('INPUT[id^="time_start_"]').each(function () {
            var name_postfix = this.name.substr(this.name.length - 3, 3);
            create_class_validate_rules[this.name] = {
                required: 'input#schedule_' + name_postfix + '[type="checkbox"]:checked',
            };
            create_class_validate_messages[this.name] = {
                required: 'Nhập giờ bắt đầu!'
            };
        });

        $(form).find('INPUT[id^="time_end_"]').each(function () {
            var name_postfix = this.name.substr(this.name.length - 3, 3);
            create_class_validate_rules[this.name] = {
                required: 'input#schedule_' + name_postfix + '[type="checkbox"]:checked'
            };
            create_class_validate_messages[this.name] = {
                required: 'Nhập giờ kết thúc!'
            };
        });

        $(form).find('INPUT[id^="schedule_"]').on('click', (e) => {
            var name_postfix = $(e.target).prop('id').substr($(e.target).prop('id').length - 3, 3);
            if ($(e.target)[0].checked == false) {
                $(form).find('INPUT[id^="time_start_' + name_postfix + '"]').val('');
                $(form).find('INPUT[id^="time_end_' + name_postfix + '"]').val('');
            }
        });

        form.validate({
            debug: true,
            success: "valid",
            rules: create_class_validate_rules,
            messages: create_class_validate_messages,
            highlight: function (element, errorClass) {
                $(element).closest(".form-group").addClass("has-error");
            },
            unhighlight: function (element, errorClass) {
                $(element).closest(".form-group").removeClass("has-error");
            },
            errorPlacement: function (error, element) {
                error.appendTo(element.parent().next());
            },
            errorPlacement: function (error, element) {
                if (element.attr("type") == "checkbox") {
                    element.closest(".form-group").children(0).prepend(error);
                } else
                    error.insertAfter(element);
            }
        });

        var table_classes = $('#list_class').DataTable({
            language: datatable_language,
            "order": [
                [1, 'asc']
            ],
            ajax: {
                url: 'api/get-list-class',
                dataSrc: 'data',
            },
            columns: [{
                    data: null
                },
                {
                    data: "name",
                    name: "name"
                },
                {
                    data: "teacher_name",
                    name: "teacher_name"
                },
                {
                    data: "max_seat",
                    name: "max_seat"
                },
                {
                    data: "start_date",
                    name: "start_date"
                },
                {
                    data: "schedule",
                    name: "schedule",
                    render: (data, type, row) => {
                        return show_schedule(data);
                    }
                },
                {
                    data: "status",
                    name: "status",
                    render: (data, type, row) => {
                        var obj_status = {
                            1: 'Chưa khai giảng',
                            2: 'Đang học',
                            3: 'Kết thúc'
                        };
                        return obj_status[data];
                    }
                }
            ],
            'autoWidth': false,
            "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": [7],
                "data": null,
                "defaultContent": table_act_buttons
            }],

        });

        table_classes.on('order.dt search.dt', function () {
            table_classes.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();

        table_classes.on('click', 'button.list-student-class', function () {
            var data = table_classes.row($(this).parents('tr')).data();
            $('DIV#modal-list-student-class DIV.modal-dialog DIV.modal-content DIV.modal-body INPUT#class_id').val(data.id);
            $('DIV#modal-list-student-class DIV.modal-dialog .modal-title').html('Danh sách học sinh của lớp <strong>' + data.name + '</strong>');
            $('DIV#modal-list-student-class').modal('show');
        });

        // table_classes.on('click', 'button.show-timetable', function () {
        //     var data = table_classes.row($(this).parents('tr')).data();
        // });

        // table_classes.on('click', 'button.show-list-exams', function () {
        //     var data = table_classes.row($(this).parents('tr')).data();
        // });

        table_classes.on('click', 'button.edit-class', function () {
            var data = table_classes.row($(this).parents('tr')).data();
            edit_class(data.id);
        });

        table_classes.on('click', 'button.delete-class', function () {
            var data = table_classes.row($(this).parents('tr')).data();
            modalConfirm((confirm) => {
                if (confirm) {
                    delete_class(data.id);
                }
            }, 'Bạn có muốn xoá lớp <strong>' + data.name + '</strong> không?')
        });

        $('DIV#modal-class UL.status_select A').on('click', (e) => {
            var text = $(e.target).text();
            var val = $(e.target).data('value');
            $('DIV#modal-class BUTTON.status_selected').text(text);
            $('DIV#modal-class INPUT#status[type="hidden"]').val(val);
        })

        $("DIV.content-class BUTTON#button-create-class").click(function () {
            reset_class_form('create');
            $('DIV#modal-class').modal('show');
        });

        var show_schedule = (json_schedule) => {
            var schedule = json_schedule ? JSON.parse(json_schedule) : null;
            var result = [];
            var vietnamese_weekday = {
                'mon': 'T2',
                'tue': 'T3',
                'wed': 'T4',
                'thu': 'T5',
                'fri': 'T6',
                'sat': 'T7',
                'sun': 'CN',
            };
            if (schedule != null) {
                for (var wd in schedule) {
                    result.push('<div>' + vietnamese_weekday[wd] + ': ' + schedule[wd].start + ' - ' + schedule[wd].finish + '</div>');
                }

                return result.join('');
            }
            return '';
        }

        var delete_class = (class_id) => {
            $.ajax({
                url: "api/delete-class",
                method: "GET",
                data: {
                    id: class_id
                },
                success: function (response) {
                    if (response.code == 1) {
                        table_classes.ajax.reload();
                        toastr.success(response.message);

                    } else
                        toastr.error(response.message);
                }
            });
        }

        var reset_class_form = (mode, data) => {
            var modal_title = '';
            if (mode == 'create') {
                modal_title = 'Thêm mới lớp học';
                $(form).find('INPUT').val('');
                $($(form).find('SELECT OPTION')[0]).prop('selected', 'selected');
                $(form).find('INPUT[id^="schedule_"][type="checkbox"]').prop('checked', false);
                status_on_select(1);
            } else if (mode == 'edit') {
                modal_title = 'Sửa thông tin lớp học';
                $(form).find('INPUT#id').val(data.id);
                $(form).find('INPUT#class_name').val(data.name);
                $(form).find('INPUT#class_code').val(data.class_code);
                $(form).find('SELECT#teacher_id').val(data.teacher_id).change();
                $(form).find('INPUT#start_date').val(data.start_date);
                $(form).find('INPUT#max_seat').val(data.max_seat);
                $(form).find('INPUT#status').val(data.status);

                status_on_select(data.status);

                $(form).find('INPUT[id^="schedule_"][type="checkbox"]').prop('checked', false);
                $(form).find('INPUT[id^="time_start_"]').val('');
                $(form).find('INPUT[id^="time_end_"]').val('');

                if (data.schedule != '') {
                    var class_schedule = JSON.parse(data.schedule);
                    for (var wd in class_schedule) {
                        $(form).find('INPUT#schedule_' + wd).prop('checked', true);
                        $(form).find('INPUT#time_start_' + wd).val(class_schedule[wd].start);
                        $(form).find('INPUT#time_end_' + wd).val(class_schedule[wd].finish);
                    }

                }
            }

            $('div.modal#modal-class DIV.modal-header .modal-title').html(modal_title);
        }

        var edit_class = (class_id) => {
            $.ajax({
                dataType: 'json',
                type: 'get',
                url: 'api/edit-class',
                data: {
                    id: class_id
                },
                success: (response) => {
                    reset_class_form('edit', response);
                    $('DIV#modal-class').modal("show");
                }
            });
        }

        var status_on_select = (value) => {
            var text = $('DIV#modal-class UL.status_select A.class_status_' + value).text();
            $('DIV#modal-class BUTTON.status_selected').text(text);
            $('DIV#modal-class INPUT#status[type="hidden"]').val(value);
        }

        var get_student_of_class = (class_id) => {
            $.ajax({
                dataType: 'json',
                type: 'get',
                url: 'api/get-list-class-student',
                data: {
                    id: class_id
                },
                success: (response) => {
                    var students_list = response.data != undefined ? response.data : [];
                    render_student_of_class(students_list);
                }
            });
        };

        var get_student_not_assign = (class_id) => {
            $('SELECT#student_not_assigned').empty();
            $.ajax({
                dataType: 'json',
                type: 'get',
                url: 'api/get-student-not-in-class',
                data: {
                    id: class_id
                },
                success: (response) => {
                    var students_list = response.data != undefined ? response.data : [];
                    if (students_list.length > 0) {
                        for(var i = 0; i < students_list.length; i++) {
                            var display_text = students_list[i].name + ' ('+ (students_list[i].birthday != null ? students_list[i].birthday : students_list[i].birthyear)  +')';
                            var opt = $('<option></option>', {value:students_list[i].id, text:display_text});
                            $('SELECT#student_not_assigned').append(opt);
                        }
                    }
                }
            });
        }

        var render_student_of_class = (students) => {
            var table = $('DIV.modal#modal-list-student-class TABLE#table-student-of-class');
            $(table).find('tbody').empty();

            if (students.length == 0) {
                $(table).find('tbody').append($('<tr><td colspan="4" style="text-align:center">Không có học sinh trong lớp</td></tr>'));
                return;
            }

            for (var i=0; i < students.length; i++) {
                var tr = $('<tr></tr>', {'data-student-id': students[i].id});
                var td_stt = $('<td></td>', {text: (i+1)});
                var td_name = $('<td></td>', {text: students[i].name});
                var td_birthday = $('<td></td>', {text: (students[i].birthday != '' ? students[i].birthday:students[i].birthyear)});
                var td_act = $('<td></td>', {text: ''});
                $(tr).append(td_stt,td_name,td_birthday,td_act);
                $(table).find('tbody').append(tr);
            }
        }

        $('DIV#modal-class BUTTON#create-class').on('click', (e) => {
            var is_valid = form.valid();
            var id = $(form).find('INPUT#id').val();
            var data = {};
            var endpoint = id != '' ? "api/edit-class" : "api/create-class";
            if (is_valid) {
                data = {
                    'name': $(form).find('INPUT#class_name').val(),
                    'class_code': $(form).find('INPUT#class_code').val(),
                    'teacher_id': $(form).find('SELECT#teacher_id').val(),
                    'course_name': $(form).find('INPUT#course_name').val(),
                    'start_date': $(form).find('INPUT#start_date').val(),
                    'max_seat': $(form).find('INPUT#max_seat').val(),
                    'status': $(form).find('INPUT#status').val(),
                    'schedule': render_schedule()
                };

                if (id != '') data.id = id;

                $.ajax({
                    url: endpoint,
                    method: "POST",
                    data: data,
                    success: (response) => {
                        table_classes.ajax.reload();
                        $('DIV#modal-class').modal("hide");
                        toastr.success(response.message);
                    }
                });
            }
        });

        $('DIV#modal-list-student-class').on('show.bs.modal', (e) => {
            var class_id = $('DIV#modal-list-student-class DIV.modal-body INPUT#class_id').val();
            get_student_not_assign(class_id);
            get_student_of_class(class_id);
        });

        $('DIV#modal-list-student-class BUTTON#btnAssignClass').on('click', (e) => {
            var students = $("SELECT#student_not_assigned").val();
            var class_id = $('DIV#modal-list-student-class DIV.modal-body INPUT#class_id').val();
            if (students.length > 0) {
                assign_to_class(class_id, students);
            }
        });

        var render_schedule = () => {
            var form = $('DIV#modal-class FORM#form-class');
            var container = $(form).find('DIV.row#schedule_row');

            var weekday_check_prefix = 'schedule_';
            var weekday_time_start_prefix = 'time_start_';
            var weekday_time_end_prefix = 'time_end_';

            var schedule = {
                'mon': {},
                'tue': {},
                'wed': {},
                'thu': {},
                'fri': {},
                'sat': {},
                'sun': {}
            };
            for (var wd in schedule) {
                if ($(container).find('INPUT#' + weekday_check_prefix + wd)[0].checked) {
                    var start_time = $(container).find('INPUT#' + weekday_time_start_prefix + wd).val();
                    var end_time = $(container).find('INPUT#' + weekday_time_end_prefix + wd).val();
                    schedule[wd] = {
                        'start': start_time,
                        'finish': end_time
                    };
                } else {
                    schedule[wd] = {};
                }
            }

            return schedule;
        };

        var assign_to_class = (class_id, students) => {
            var data = {
                class_id: class_id,
                students: students
            }
            $.ajax({
                url : "api/add-student-to-class",
                type: "POST",
                data : data,
                success:function(response){
                    if(response.code == 0){
                        get_student_not_assign(class_id);
                        get_student_of_class(class_id);
                        toastr.error(response.message);
                    }else{
                        get_student_not_assign(class_id);
                        get_student_of_class(class_id);
                        toastr.success(response.message);
                    }
                }
            });
        }

        $("SELECT#teacher_id").empty();

        $("SELECT#student_not_assigned").select2({
            placeholder: "Chọn học sinh",
            minimumInputLength: 3
        });

        $.ajax({
            dataType: 'json',
            type: 'get',
            url: 'api/get-name-teacher',
            success: function (response) {
                $.each(response.data, function () {
                    $("DIV#modal-class SELECT#teacher_id").append("<option value=" + this.id + ">" + this.name + "</option>")
                });
            }
        });

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
    }
});