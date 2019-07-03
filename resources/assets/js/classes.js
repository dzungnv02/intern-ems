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

//require('./attendance.js');

$(function () {
    if ($('TABLE#list_class').length > 0) {

        var form = $('DIV#modal-class FORM#form-class');
        var attendance_modal = $('DIV#attendance-modal');

        var table_act_buttons = '<button type="button" title="Danh sách lớp" class="list-student-class btn btn-sm btn-info"><i class="fa fa-address-book-o" aria-hidden="true"></i></button>\
                            <button type="button"  title="Thời Khóa Biểu" class="show-timetable btn btn-sm bg-purple"><i class="fa fa-calendar-times-o" aria-hidden="true"></i></button>\
                            <button type="button" title="Sửa thông tin lớp" class="edit-class btn btn-sm btn-primary"><i title="Sửa thông tin lớp" class="fa fa-pencil-square-o" aria-hidden="true"></i></button>\
                            <button type="button" title="Điểm danh" class="attendance-class btn btn-sm btn-warning"><i class="fa fa-check" aria-hidden="true"></i></button>';
                            // <button type="button" title="Xoá lớp" class="delete-class btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';

        var vietnamese_weekday = {
            'mon': 'T2',
            'tue': 'T3',
            'wed': 'T4',
            'thu': 'T5',
            'fri': 'T6',
            'sat': 'T7',
            'sun': 'CN',
        };

        var vietnamese_weekday_full = {
            'mon': 'Thứ 2',
            'tue': 'Thứ 3',
            'wed': 'Thứ 4',
            'thu': 'Thứ 5',
            'fri': 'Thứ 6',
            'sat': 'Thứ 7',
            'sun': 'Chủ nhật',
        };

        var date_range_time_table = $('DIV#modal-time-table DIV.modal-body INPUT#reservation').daterangepicker({
            "locale": {
                "format": "YYYY-MM-DD",
                "separator": " - ",
                "applyLabel": "Chọn",
                "cancelLabel": "Đóng",
                "fromLabel": "From",
                "toLabel": "To",
                "customRangeLabel": "Custom",
                "daysOfWeek": [
                    "CN",
                    "Th2",
                    "Th3",
                    "Th4",
                    "Th5",
                    "Th6",
                    "Th7"
                ],
                "monthNames": [
                    "Tháng 1",
                    "Tháng 2",
                    "Tháng 3",
                    "Tháng 4",
                    "Tháng 5",
                    "Tháng 6",
                    "Tháng 7",
                    "Tháng 8",
                    "Tháng 9",
                    "Tháng 10",
                    "Tháng 11",
                    "Tháng 12"
                ],
                "firstDay": 1
            },
            autoUpdateInput: false
        });

        date_range_time_table.on('apply.daterangepicker', function (e, picker) {
            $(e.target).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            $('DIV#modal-time-table DIV.modal-body INPUT#start_date').val(picker.startDate.format('YYYY-MM-DD'));
            $('DIV#modal-time-table DIV.modal-body INPUT#end_date').val(picker.endDate.format('YYYY-MM-DD'));
            $('DIV#modal-time-table DIV.modal-body BUTTON#btnCalc').prop('disabled', false);
        });

        $('DIV#modal-time-table DIV.modal-body INPUT#reservation').on('change', (e) => {
            if ($(e.target).val() == '') {
                $('DIV#modal-time-table DIV.modal-body BUTTON#btnCalc').prop('disabled', true);
            }
        })

        $('SELECT.select2[id="teacher_id"]').select2({
            placeholder: "Chọn giáo viên",
        });

        var create_class_validate_rules = {
            class_name: {
                required: true
            },
            /* max_seat: {
                required: true,
                number: true,
                min: 1
            }, */
            start_date: {
                required: true,
                date: true
            }
        }

        var create_class_validate_messages = {
            class_name: {
                required: "Tên lớp không được trống!"
            },
            /* max_seat: {
                required: "Số học sinh tối đa không được trống!",
                number: "Hãy nhập giá trị là số lớn hơn 0!",
                min: "Hãy nhập giá trị là số lớn hơn 0!"
            }, */
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

        var create_class_validator = form.validate({
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

        var table_classes = $('TABLE#list_class').DataTable({
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
                    data: "seat_count",
                    name: "seat_count"
                },
                {
                    data: "start_date",
                    name: "start_date",
                    render: (data, type, row) => {
                        return data != null ? moment(data).format("DD/MM/YYYY") : '';
                    }
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
                            0: 'Chưa khai giảng',
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
            $('DIV#modal-list-student-class DIV.modal-body INPUT#class_id').val(data.id);
            $('DIV#modal-list-student-class DIV.modal-dialog .modal-title').html('Danh sách học sinh của lớp <strong>' + data.name + '</strong>');
            $('DIV#modal-list-student-class').modal('show');
        });

        table_classes.on('click', 'button.show-timetable', function () {
            var data = table_classes.row($(this).parents('tr')).data();
            $('DIV#modal-time-table DIV.modal-body INPUT#class_id').val(data.id);
            $('DIV#modal-time-table').modal('show');
        });

        table_classes.on('click', 'button.attendance-class', function () {
            var data = table_classes.row($(this).parents('tr')).data();
            $(attendance_modal).find('H3.modal-title').html('Điểm danh cho lớp ' + data.name);
            $(attendance_modal).find('input#current-class-id').val(data.id);
            $(attendance_modal).find('input#current-class-name').val(data.name);
            open_attendance(data.id);
        });

        table_classes.on('click', 'button.edit-class', function () {
            var data = table_classes.row($(this).parents('tr')).data();
            edit_class(data.id);
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

            if (schedule != null) {
                for (var wd in schedule) {
                    schedule[wd].start = schedule[wd].start.length < 5 ? '0' + schedule[wd].start : schedule[wd].start;
                    schedule[wd].finish = schedule[wd].finish.length < 5 ? '0' + schedule[wd].finish : schedule[wd].finish;
                    result.push('<div>' + vietnamese_weekday[wd] + ': ' + schedule[wd].start + ' - ' + schedule[wd].finish + '</div>');
                }

                return result.join('');
            }
            return '';
        }

        var reset_class_form = (mode, data) => {
            var modal_title = '';
            $(form)[0].reset();
            create_class_validator.resetForm();
            form.find(".error").removeClass("error");

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
                $(form).find('INPUT#course_name').val(data.course_name);

                status_on_select(data.status);

                $(form).find('INPUT[id^="schedule_"][type="checkbox"]').prop('checked', false);
                $(form).find('INPUT[id^="time_start_"]').val('');
                $(form).find('INPUT[id^="time_end_"]').val('');

                if (data.schedule != '') {
                    var class_schedule = JSON.parse(data.schedule);
                    for (var wd in class_schedule) {
                        class_schedule[wd].start = class_schedule[wd].start.length < 5 ? '0' + class_schedule[wd].start : class_schedule[wd].start;
                        class_schedule[wd].finish = class_schedule[wd].finish.length < 5 ? '0' + class_schedule[wd].finish : class_schedule[wd].finish;
                        $(form).find('INPUT#schedule_' + wd).prop('checked', true);
                        $(form).find('INPUT#time_start_' + wd).val(moment('1900-01-01 ' + class_schedule[wd].start).format("LT"));
                        $(form).find('INPUT#time_end_' + wd).val(moment('1900-01-01 ' +class_schedule[wd].finish).format("LT"));
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
                    render_student_of_class(students_list, class_id);
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
                        for (var i = 0; i < students_list.length; i++) {
                            var display_text = students_list[i].name + ' (' + (students_list[i].birthday != null ? students_list[i].birthday : students_list[i].birthyear) + ')';
                            var opt = $('<option></option>', {
                                value: students_list[i].id,
                                text: display_text
                            });
                            $('SELECT#student_not_assigned').append(opt);
                        }
                    }
                }
            });
        }

        var render_student_of_class = (students, class_id) => {
            var table = $('DIV.modal#modal-list-student-class TABLE#table-student-of-class');
            $(table).find('tbody').empty();

            if (students.length == 0) {
                $(table).find('tbody').append($('<tr><td colspan="4" style="text-align:center">Không có học sinh trong lớp</td></tr>'));
                return;
            }

            for (var i = 0; i < students.length; i++) {
                var tr = $('<tr></tr>', {
                    'data-student-id': students[i].id
                });
                var td_stt = $('<td></td>', {
                    text: (i + 1)
                });
                var td_name = $('<td></td>', {
                    text: students[i].name
                });
                var td_birthday = $('<td></td>', {
                    text: (students[i].birthday != '' ? students[i].birthday : students[i].birthyear)
                });

                var button = $('<button></button>', {
                    type: 'button',
                    title: 'Xoá khỏi danh sách lớp',
                    class: 'btn btn-sm btn-danger',
                    'data-student': students[i].id,
                    'data-class': class_id
                }).append($('<i class="fa fa-trash-o" aria-hidden="true"></i>'));
                var td_act = $('<td></td>', {
                    text: ''
                }).append(button);

                $(button).bind('click', (e) => {
                    console.log(e.currentTarget);
                    var student_id = $(e.currentTarget).data('student');
                    var class_id = $(e.currentTarget).data('class');
                    remove_student_class(class_id, student_id);
                });

                $(tr).append(td_stt, td_name, td_birthday, td_act);
                $(table).find('tbody').append(tr);
            }
        }

        var remove_student_class = (class_id, student_id) => {
            $.ajax({
                dataType: 'json',
                type: 'get',
                url: 'api/delete-student-class',
                data: {
                    class_id: class_id,
                    student_id: student_id
                },
                success: (response) => {
                    var table = $('DIV.modal#modal-list-student-class TABLE#table-student-of-class');
                    var tr = $(table).find('tbody tr td button[data-student="' + student_id + '"]').parent().parent().remove();

                    if ($(table).find('tbody tr').length == 0) {
                        $(table).find('tbody').append($('<tr><td colspan="4" style="text-align:center">Không có học sinh trong lớp</td></tr>'));
                    }
                }
            });
        }

        var calc_time_table = (class_id, start_date, end_date, callback) => {
            data = {
                'class_id': class_id,
                'start_date': start_date,
                'end_date': end_date
            };

            $.ajax({
                url: 'api/calc_time_table',
                dataType: 'json',
                data: data,
                type: 'GET',
                success: function (response) {
                    time_table_init(response.data);
                }
            }).done(function () {
                callback();
            });
        }

        var time_table_init = (list) => {
            var my_lang = datatable_language;
            my_lang.emptyTable = 'Chưa có thời khoá biểu';
            var buttons = '<button type="button" title="Điểm danh" class="btn btn-warning attendance"><i class="fa fa-check-square-o"></i></button>\
            <button type="button" title="Nhật ký" class="btn btn-info teaching-diary"><i class="fa fa-sticky-note"></i></button>\
            <button type="button" title="Chỉnh sửa" class="btn bg-olive edit"><i class="fa fa-pencil-square-o"></i></button>';

            var table_timetable = null;
            if ($.fn.dataTable.isDataTable('DIV#modal-time-table TABLE#table-timetable')) {
                table_timetable = $('DIV#modal-time-table TABLE#table-timetable').DataTable();
                table_timetable.clear();
                table_timetable.rows.add(list);
                table_timetable.draw();
            } else {
                var table_timetable = $('DIV#modal-time-table TABLE#table-timetable').DataTable({
                    data: list,
                    language: my_lang,
                    'autoWidth': false,
                    "ordering": false,
                    "searching": false,
                    "bPaginate": true,
                    "columnDefs": [{
                            "searchable": false,
                            "orderable": false
                        },
                        {
                            targets: [4],
                            "data": null,
                            "visible": true,
                            "defaultContent": buttons
                        }
                    ],
                    columns: [{
                            data: null
                        },
                        {
                            data: "date",
                            name: "date",
                            render: (data, type, row) => {
                                var wd = '<span> (' + vietnamese_weekday_full[row.week_day] + ')</span>';
                                if (row.week_day == 'sun') wd = '<span style="color:red"> (' + vietnamese_weekday_full[row.week_day] + ')</span>'
                                return data + wd;
                            }
                        },
                        {
                            render: (data, type, row) => {
                                return row.start + ' - ' + row.finish;
                            }
                        },
                        {
                            data: "teacher_name",
                            name: "teacher_name"
                        },
                        {
                            data: null
                        },
                    ]
                });

                table_timetable.on('draw.dt order.dt search.dt', function () {
                    table_timetable.column(0, {}).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();

                table_timetable.on('click', 'button.attendance', function () {
                    var data = table_timetable.row($(this).parents('tr')).data();
                    console.log('rollcall', data);
                });

                table_timetable.on('click', 'button.teaching-diary', function () {
                    var data = table_timetable.row($(this).parents('tr')).data();
                    console.log('teaching-diary', data);
                });

                table_timetable.on('click', 'button.edit', function () {
                    var data = table_timetable.row($(this).parents('tr')).data();
                    console.log('edit', data);
                });
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
                    }
                });
            }
        });

        $('DIV#modal-time-table DIV.modal-footer BUTTON#btnSave').on('click', (e) => {
            if ($.fn.dataTable.isDataTable('DIV#modal-time-table TABLE#table-timetable')) {
                $(e.target).prop('disabled', true);
                $(e.target).button('loading');

                var table_timetable = $('DIV#modal-time-table TABLE#table-timetable').DataTable();
                var data = {
                    'class_id': $('DIV#modal-time-table DIV.modal-body INPUT#class_id').val(),
                    'start_date': $('DIV#modal-time-table DIV.modal-body INPUT#start_date').val(),
                    'end_date': $('DIV#modal-time-table DIV.modal-body INPUT#end_date').val(),
                    'time_table': []
                };
                var time_table = [];
                table_timetable.data().each(function (d) {
                    time_table.push(d);
                });

                data.time_table = time_table;

                var url = 'api/save-time-table';
                $.ajax({
                    url: url,
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    dataType: 'json',
                    method: 'POST',
                    success: (response) => {
                        $(e.target).prop('disabled', false);
                        $(e.target).button('reset');
                        $('DIV#modal-time-table').modal('hide');
                    }
                });

            }
        });

        $('DIV#modal-time-table DIV.modal-body BUTTON#btnCalc').on('click', (e) => {
            var class_id = $('DIV#modal-time-table DIV.modal-body INPUT#class_id').val();
            var start_date = $('DIV#modal-time-table DIV.modal-body INPUT#start_date').val();
            var end_date = $('DIV#modal-time-table DIV.modal-body INPUT#end_date').val();

            $(e.target).button('loading');
            $(e.target).prop('disabled', true);

            calc_time_table(class_id, start_date, end_date, () => {
                $('DIV#modal-time-table DIV.modal-body BUTTON#btnCalc').prop('disabled', false);
                $('DIV#modal-time-table DIV.modal-body BUTTON#btnCalc').button('reset');
            });
        });

        $('DIV#modal-list-student-class').on('show.bs.modal', (e) => {
            var class_id = $('DIV#modal-list-student-class DIV.modal-body INPUT#class_id').val();
            get_student_not_assign(class_id);
            get_student_of_class(class_id);
        });

        $('DIV#modal-list-student-class').on('hide.bs.modal', (e) => {
            var table = $('DIV.modal#modal-list-student-class TABLE#table-student-of-class');
            $(table).find('tbody').empty();
            $(table).find('tbody').append($('<tr><td colspan="4" style="text-align:center">Không có học sinh trong lớp</td></tr>'));
        });

        $('DIV#modal-class').on('show.bs.modal', (e) => {
            create_class_validator.resetForm();
            form.find(".has-error").removeClass("has-error");
            form.find(".has-error").removeClass("has-error");
        });

        $('DIV#modal-time-table').on('hide.bs.modal', (e) => {
            if ($.fn.dataTable.isDataTable('DIV#modal-time-table TABLE#table-timetable')) {
                var table_timetable = $('DIV#modal-time-table TABLE#table-timetable').DataTable();
                table_timetable.clear();
                table_timetable.rows.add([]);
                table_timetable.draw();
            }
        });

        $('DIV#modal-time-table').on('show.bs.modal', (e) => {
            var start_date = $('DIV#modal-time-table DIV.modal-body INPUT#start_date').val();
            var end_date = $('DIV#modal-time-table DIV.modal-body INPUT#end_date').val();
            var class_id = $('DIV#modal-time-table DIV.modal-body INPUT#class_id').val();

            if ($('DIV#modal-time-table DIV.modal-body INPUT#start_date').val() != '')
                $('DIV#modal-time-table DIV.modal-body BUTTON#btnCalc').prop('disabled', false);
            else
                $('DIV#modal-time-table DIV.modal-body BUTTON#btnCalc').prop('disabled', true);

            $.ajax({
                url: 'api/get-list-timetable',
                method: 'GET',
                data: {
                    'class_id': class_id
                },
                success: (response) => {
                    time_table_init(response.data);
                }
            });
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
                        'start': moment(start_time, "h:mm A").format("HH:mm"),
                        'finish': moment(end_time, "h:mm A").format("HH:mm")
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
                url: "api/add-student-to-class",
                method: "POST",
                data: data,
                success: function (response) {
                    if (response.code == 0) {
                        get_student_not_assign(class_id);
                        get_student_of_class(class_id);
                    } else {
                        get_student_not_assign(class_id);
                        get_student_of_class(class_id);
                    }
                }
            });
        }

        $("SELECT#teacher_id").empty();

        $("SELECT#student_not_assigned").select2({
            placeholder: "Chọn học sinh",
            minimumInputLength: 3
        });

        var contaner = $('DIV#attendance-modal');

        var attendanceCheck = (timetable_id, student_id, status, callback) => {
            var data = {
                'timetable_id': timetable_id,
                'student_id': student_id,
                'status': status
            };

            $.ajax({
                url: '/api/class/attendance/check',
                dataType: 'json',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: (response) => {
                    if (callback != undefined && response.code == 1) {
                        callback(response.data);
                    }
                    console.log('response', response);
                }
            });
        };

        var get_attendance_list = (class_id, callback)  => {
            $.ajax({
                url: '/api/class/attendance?class_id=' + class_id,
                dataType: 'json',
                method: 'GET',
                contentType: 'application/json',
                success: (response) => {
                    if (callback != undefined && response.code == 1) {
                        callback(response.data);
                    }
                    console.log('response', response);
                }
            });
        }

        var get_attendance_by_timetable = (timetable_id, callback)  => {
            $.ajax({
                url: '/api/class/attendance/by-date?timetable_id=' + timetable_id,
                dataType: 'json',
                method: 'GET',
                contentType: 'application/json',
                success: (response) => {
                    if (callback != undefined && response.code == 1) {
                        callback(response.data);
                    }
                    console.log('response', response);
                }
            });
        }

        var get_timetable = (class_id, callback) => {
            $.ajax({
                url: '/api/get-list-timetable?class_id=' + class_id,
                dataType: 'json',
                contentType: 'application/json',
                success: (response) => {
                    if (callback != undefined && response.code == 1) {
                        callback(response.data, class_id);
                    }
                    console.log('response', response);
                }
            });
        }
    
        var render_attendance_table = (list) => {
            var list_container = $(contaner).find('TABLE#attendance-table tbody');
            list_container.empty();
            if (list.length > 0) {
                for (var i = 0; i < list.length; i++) {
                    var tr = $('<tr></tr>');
                    var item = list[i];
                    var td_no = $('<th></th>', {text: i + 1, 'style': 'text-align:center'});
                    $(tr).append(td_no);
                    for (var attr in item) {
                        if (attr == 'id' || attr == 'status') continue; 
                        var style = '';
                        if (attr == 'present' || attr == 'absent'|| attr == 'late') {
                            style = 'text-align:center';
                        }
                        var td = $('<td></td>', {text: item[attr], 'style': style});
                        $(tr).append(td);
                    }

                    var td_input_present = $('<td></td>', {'style': 'text-align:center', html: '<input type="radio" data-student="'+item.id+'" value="1" name="'+item.id+'_status">'});
                    var td_input_absent = $('<td></td>', {'style': 'text-align:center', html: '<input type="radio" data-student="'+item.id+'" value="-1" name="'+item.id+'_status">'});
                    var td_input_late = $('<td></td>', {'style': 'text-align:center', html: '<input type="radio" data-student="'+item.id+'" value="-2" name="'+item.id+'_status">'});
    
                    $(tr).append(td_input_present, td_input_absent, td_input_late);
                    $(list_container).append(tr);

                    var radio_present = $(td_input_present).find('INPUT[name="'+item.id+'_status"]');
                    var radio_absent = $(td_input_absent).find('INPUT[name="'+item.id+'_status"]');
                    var radio_late = $(td_input_late).find('INPUT[name="'+item.id+'_status"]');

                    $(radio_present).on('click', (e) => {
                        var timetable_id = $(attendance_modal).find('SELECT#timetable_id').val();
                        var student_id = $(e.target).data('student');
                        var status = $(e.target).val();
                        attendanceCheck(timetable_id, student_id, status);
                    });

                    $(radio_absent).on('click', (e) => {
                        var timetable_id = $(attendance_modal).find('SELECT#timetable_id').val();
                        var student_id = $(e.target).data('student');
                        var status = $(e.target).val();
                        attendanceCheck(timetable_id, student_id, status);
                    });

                    $(radio_late).on('click', (e) => {
                        var timetable_id = $(attendance_modal).find('SELECT#timetable_id').val();
                        var student_id = $(e.target).data('student');
                        var status = $(e.target).val();
                        attendanceCheck(timetable_id, student_id, status);
                    });
                }
            }
        }
    
        var open_attendance = (class_id) => {
            get_timetable(class_id, (timetables, class_id) => {
                if (timetables.length > 0) {
                    var timetable_id = $(attendance_modal).find('SELECT#timetable_id');
                    $(timetable_id).empty();
                    for (var i = 0; i < timetables.length; i++) {
                        var timetable_opt = $('<option></option>', {value:timetables[i].id, text: timetables[i].date + ' (' + timetables[i].start + ' - ' + timetables[i].finish +')'});
                        $(timetable_id).append(timetable_opt);
                    }

                    get_attendance_list(class_id, (list) => {
                        render_attendance_table(list);
                        $(attendance_modal).modal('show');
                    });
                }
                else {
                    var msg = 'Lớp ' + $(attendance_modal).find('input#current-class-name').val() + ' chưa thiết lập thời khoá biểu!';
                    alert(msg);
                    return;
                }
            });
        }

        var fill_attendance = (data) => {
            $(attendance_modal).find('TABLE#attendance-table TBODY INPUT[type="radio"]').prop('checked', false);
            if (data.length > 0) {
                for(var i = 0; i < data.length; i++) {
                    var attendance = data[i];
                    $(attendance_modal).find('TABLE#attendance-table TBODY INPUT[type="radio"][data-student="'+ attendance.student_id +'"][value="'+attendance.status+'"]').prop('checked', true);
                }
            }
        }

        $(attendance_modal).on('show.bs.modal', (e) => {
            $(attendance_modal).find('SELECT#timetable_id').trigger('change');
        });

        $(attendance_modal).find('SELECT#timetable_id').change((e) => {
            var timetable_id = $(attendance_modal).find('SELECT#timetable_id').val();
            get_attendance_by_timetable(timetable_id, (data) => {
                fill_attendance(data);
            });
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

        $(form).find('INPUT[type="text"][id^="time_start_"],INPUT[type="text"][id^="time_end_"]').timepicker({
            showInputs: false,
            template: 'dropdown',
        }).on('show.timepicker', (e) => {
            if ($(e.target).val() != '') {
                $(e.target).timepicker('setTime', $(e.target).val());
            }
            else {
                $(e.target).val(e.time.value); 
            }
        }).on('changeTime.timepicker', function(e) {
            $(e.target).parent().find('label').remove();
        });
    }
});