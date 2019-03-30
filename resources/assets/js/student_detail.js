$(function () {
    var tab_headers = $('UL.nav#student_detail');
    var tab_contents = $('DIV.tab-content');
    var student_id = null;

    var student_data = {
        profile: null,
        parents: null,
        activities: null,
        exams: null,
        teacher_reports: null,
        payment: null,
        attendance: null
    }

    var assessment_status = {
        '-1': 'Chưa hẹn lịch',
        '0': 'Chưa có kết quả',
        '1': 'Đã có kết quả'
    };

    var trial_status = {
        '0': 'Chưa có lịch học thử',
        '1': 'Đã hẹn học thử',
        '2': 'Đã học thử'
    };

    var activity_types = {
        '1': 'Làm assessment',
        '2': 'Học thử',
        '3': 'Nhập học chính thức',
        '4': 'Đã gửi nội dung học 2 tuần tới',
        '5': 'Chuyển lớp',
        '6': 'Kiểm tra',
        '7': 'Kết thúc'
    };

    if (tab_headers.length == 0) return false;

    var tab_col = $(tab_headers).find('LI');

    var tab_activate = (target) => {
        $(tab_col).removeClass('active');
        $(target).parent().addClass('active');
        $(tab_contents).find("DIV[role='tabpanel']").removeClass('active');
        $(tab_contents).find("DIV[role='tabpanel']#" + $(target).data('tab')).addClass('active');
    }

    $(tab_headers).find('A').bind('click', (e) => {
        tab_activate(e.target);
        e.preventDefault();
        e.stopPropagation();
    });

    $('BUTTON#btnSaveAll').on('click', (e) => {
        save();
    })

    $('BUTTON#btn_change_parent').on('click', (e) => {
        tab_activate($(tab_col).find('a[data-tab="parents"]'));
    });

    $('A#btnSaveActivity').on('click', (e) => {
        save_activities();
    });

    var get_url_param = ($param_name) => {
        var hash;
        var q = document.URL.split('?')[1];
        if (q != undefined) {
            q = q.split('&');
            for (var i = 0; i < q.length; i++) {
                hash = q[i].split('=');
                if (hash[0] == $param_name) return hash[1];
            }
            return null;
        }
        return null;
    }

    var get_student_profile = (callback) => {
        get('/api/student/get-profile', {
            id: student_id
        }, (data) => {
            student_data.profile = data;
            fill_profile(data);
            if (callback != undefined) callback();
        });
    }

    var get_parent_list = (callback) => {
        get('/api/parent/list', {
            student_id: student_id
        }, (data) => {
            student_data.parents = data;
            fill_parent_list(data);
            if (callback != undefined) callback();
        });
    }

    var get_exam_results = () => {

    }

    var get_teacher_reports = () => {

    }

    var get_payment_history = () => {

    }

    var get_activities = () => {
        get('/api/student/get-activity', {
            student_id: student_id
        }, (data) => {
            student_data.activities = data;
            fill_activities(data);
        });
    }

    var get_attendance_history = () => {
        get('/api/student/attendance', {student_id: student_id} ,(data) => {
            student_data.attendance = data;
            fill_attendance(data);
        });
    }

    var fill_profile = (data) => {
        var form = $('FORM#frmStudent');
        if (data.student !== null) {
            $(form).find('INPUT#student-name').val(data.student.name);
            $(form).find('INPUT#student-e_name').val(data.student.e_name);
            $(form).find('INPUT#student-gender').val(data.student.gender);
            $(form).find('INPUT#student-birthyear').val(data.student.birthyear);
            $(form).find('INPUT#student-birthday').val(data.student.birthday);
        }

        if (data.parent !== null) {
            $(form).find('INPUT#parent_id').val(data.parent.id);
            $(form).find('INPUT#parent-fullname').val(data.parent.fullname);
            $(form).find('INPUT#parent-parent_role').val(data.parent.parent_role);
            $(form).find('INPUT#parent-phone').val(data.parent.phone);
            $(form).find('INPUT#parent-email').val(data.parent.email);
            $(form).find('INPUT#parent-facebook').val(data.parent.facebook);
        }

        if (data.staff !== null) {
            $(form).find('INPUT#dependent_staff_name').val(data.staff.name);
            $(form).find('INPUT#staff_id').val(data.staff.id);
        }

        if (data.register_branch !== null) {
            $(form).find('INPUT#branch-register_branch_name').val(data.register_branch.branch_name);
            $(form).find('INPUT#register_branch_id').val(data.register_branch.id);
        }

        if (data.dependent_branch !== null) {
            $(form).find('INPUT#branch-dependent_branch_name').val(data.dependent_branch.branch_name);
            $(form).find('INPUT#dependent_branch_id').val(data.dependent_branch.id);
        }

        if (data.assessment !== null) {
            $(form).find('INPUT#assessment_status').val(assessment_status[data.assessment.status]);
            $(form).find('INPUT#assessment_date').val(moment(data.assessment.assessment_date).format("YYYY-MM-DDTkk:mm"));
            $(form).find('INPUT#assessment_teacher').val(data.assessment.teacher_name);
            $(form).find('INPUT#assessment_teacher_id').val(data.assessment.teacher_id);
            $(form).find('INPUT#assessment_result').val(data.assessment.assessment_result);

            $(form).find('INPUT#trial_status').val(trial_status[data.assessment.trial_status]);
            $(form).find('INPUT#trial_start_date').val(moment(data.assessment.trial_start_date).format("YYYY-MM-DDTkk:mm"));
            $(form).find('INPUT#trial_class').val(data.assessment.class_name);
            $(form).find('INPUT#assessment_trial_class_id').val(data.assessment.trial_class_id);

        } else {
            $(form).find('INPUT#assessment_status').val(assessment_status['-1']);
            $(form).find('INPUT#trial_status').val(trial_status['0']);
        }

    }

    var fill_activities = (data) => {
        var table = $('DIV.box#box_history > DIV.box-body > TABLE');
        $(table).find('tbody').empty();
        if (data.length > 0) {
            for (var i = 0; i < data.length; i++) {
                var obj = data[i];
                var tr = $('<TR></TR>');
                var index_cell = $('<TH></TH>', {
                    text: i + 1
                });
                var activity_cell = $('<TD></TD>', {
                    text: activity_types[obj.act_type]
                });
                var note_cell = $('<TD></TD>', {
                    text: obj.note
                });
                var start_date_cell = $('<TD></TD>', {
                    text: obj.start_time
                });
                var act_cell = $('<TD></TD>');
                // var act_edit = $('<a></a>', {html:'<i class="fa fa-pencil"></i>', class:'btn btn-sm btn-info', role:'button'});
                var act_del = $('<a></a>', {
                    html: '<i class="fa fa-remove"></i>',
                    class: 'btn btn-sm btn-danger',
                    role: 'button'
                });
                $(tr).append(index_cell, activity_cell, note_cell, start_date_cell, act_cell);
                $(table).find('tbody').append(tr);
            }
        } else {
            var tr = $('<TR></TR>');
            var cell = $('<TD></TD>', {
                colspan: 5,
                text: 'Không có hoạt động nào',
                style: 'text-align:center'
            });
            $(table).find('tbody').append(tr.append(cell));
        }
    }

    var fill_parent_list = (data) => {
        var table = $('DIV.box#box_parent_list > DIV.box-body > TABLE');
        $(table).find('tbody').empty();
        if (data.length > 0) {
            for (var i = 0; i < data.length; i++) {
                var obj = data[i];
                var set_primary_button = $('<a></a>', {
                    class: 'text-white',
                    text: 'Chọn',
                    title: 'Chọn làm liên hệ chính',
                    style: 'color:white;cursor: pointer',
                    'data-parent': JSON.stringify(obj)
                });
                var btn_container = $('<span></span>', {
                    class: 'badge bg-dark',
                    style: 'margin-left:30px'
                }).append(set_primary_button);

                var primary_contact = obj.id == student_data.profile.parent.id ?
                    $('<span style="margin-left:30px" title="" class="badge bg-light-blue"><i class="fa fa-check"></span></i>') : btn_container;

                var tr = $('<TR></TR>');
                var index_cell = $('<TH></TH>', {
                    text: i + 1
                });
                var role_cell = $('<TD></TD>', {
                    text: obj.parent_role
                });
                var name_cell = $('<TD></TD>', {
                    text: obj.fullname
                });
                var phone_cell = $('<TD></TD>', {
                    text: obj.phone
                });
                var email_cell = $('<TD></TD>', {
                    html: obj.email != null ? '<a href="mailto:' + obj.email + '">' + obj.email + '</a>' : ''
                });
                var address_cell = $('<TD></TD>', {
                    text: obj.address
                });
                var primary_contact_cell = $('<TD></TD>', {
                    html: primary_contact
                });
                var act_edit = $('<a></a>', {
                    text: 'Thay đổi'
                });
                var act_del = $('<a></a>', {
                    text: 'Xoá'
                });
                var act_cell = $('<TD></TD>').append(act_edit, '&nbsp;|&nbsp;', act_del);

                $(tr).append(index_cell, role_cell, name_cell, phone_cell, email_cell, address_cell, primary_contact_cell, act_cell);
                $(table).find('tbody').append(tr);

                $(set_primary_button).on('click', (e) => {
                    set_primary_parent($(e.target).data('parent'));
                })
            }
        } else {
            var tr = $('<TR></TR>');
            var cell = $('<TD></TD>', {
                colspan: 8,
                text: 'Không có danh sách phụ huynh',
                style: 'text-align:center'
            });
            $(table).find('tbody').append(tr.append(cell));
        }

    }

    var fill_exam_results = (data) => {

    }

    var fill_teacher_reports = (data) => {

    }

    var fill_payment_history = (data) => {

    }

    var fill_attendance = (data) => {
        var container = $('DIV#box_attendance');
        var table = $(container).find('TABLE > TBODY');
        $(table).empty();

        if (data.length > 0) {
            for (var i = 0; i < data.length; i++) {
                var cls = data[i];
                
                var class_cell = $('<td></td>', {
                    'colspan': '6',
                    'class': 'text-left',
                    'html': '<h4>Lớp <b>' + cls.name + '</b></h4>'
                });

                var class_row = $('<tr></tr>').append(class_cell);

                $(table).append(class_row);

                if (cls.attendance.length > 0) {
                    for (var x = 0; x < cls.attendance.length; x++) {
                        var attend = cls.attendance[x];
                        var attend_row = $('<tr></tr>');
                        var td_num = $('<td></td>', {
                            'text': x + 1
                        });

                        var td_date = $('<td></td>', {
                            'text': attend.date
                        });

                        var td_present = $('<td></td>', {
                            'html': attend.present !== '' ? '<i class="fa fa-check text-success" style="font-size: 1.5em"></i>' : ''
                        });

                        var td_absent = $('<td></td>', {
                            'html': attend.absent !== '' ? '<i class="fa fa-check text-danger" style="font-size: 1.5em"></i>' : ''
                        });

                        var td_late = $('<td></td>', {
                            'html': attend.late !== '' ? '<i class="fa fa-check text-warning" style="font-size: 1.5em"></i>' : ''
                        });

                        var td_note = $('<td></td>', {
                            'text': attend.note
                        });

                        $(attend_row).append(td_num, td_date, td_present, td_absent, td_late, td_note);
                        $(table).append(attend_row);
                    }
                }
                else {
                    var class_cell = $('<td></td>', {
                        'colspan': '6',
                        'class': 'text-center',
                        'text': 'Không có dữ liệu'
                    });
        
                    var class_row = $('<tr></tr>').append(class_cell);
                    $(table).append(class_row);
                }
            }
        }
        else {
            var class_cell = $('<td></td>', {
                'colspan': '6',
                'class': 'text-center',
                'text': 'Không có dữ liệu'
            });

            var class_row = $('<tr></tr>').append(class_cell);
            $(table).append(class_row);
        }


    }

    var get = (end_point, data, callback) => {
        $.ajax({
            url: end_point + '/?' + $.param(data, true),
            method: 'GET',
            dataType: 'json',
            //contentType: 'application/json',
            success: (response) => {
                if (response.code == 1) {
                    callback(response.data);
                } else {
                    console.log(response.message);
                }
            }
        });
    };

    var post = (end_point, data, callback) => {
        $.ajax({
            url: end_point,
            method: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: (response) => {
                if (response.code == 1) {
                    callback(response.data);
                } else {
                    console.log(response.message);
                }
            }
        });
    };

    var save_profile = () => {
        var form = $('FORM#frmStudent');
        var assessment_date = $(form).find('INPUT#assessment_date').val().trim();
        assessment_date = assessment_date == '' ? null : moment(assessment_date).format("YYYY-MM-DD HH:mm:ss");
        console.log(assessment_date);
        var data = {
            'student_id': student_id,
            'student': {
                'name': $(form).find('INPUT#student-name').val().trim(),
                'e_name': $(form).find('INPUT#student-e_name').val().trim(),
                'gender': $(form).find('SELECT#student-gender').val().trim(),
                'birthyear': $(form).find('INPUT#student-birthyear').val().trim(),
                'birthday': $(form).find('INPUT#student-birthday').val().trim(),
                'parent_id': $(form).find('INPUT#parent_id').val().trim(),
                'register_branch_id': $(form).find('INPUT#register_branch_id').val().trim(),
                'dependent_branch_id': $(form).find('INPUT#dependent_branch_id').val().trim(),
                'staff_id': $(form).find('INPUT#staff_id').val().trim(),
                'register_note': $(form).find('textarea#register_note').val().trim(),
            },
            'assessment': {
                'assessment_date': assessment_date,
                'assessment_result': $(form).find('INPUT#assessment_result').val().trim(),
                'teacher_id': $(form).find('INPUT#assessment_teacher_id').val().trim(),
                'trial_class_id': $(form).find('INPUT#assessment_teacher_id').val().trim(),
            }
        }

        post('/api/student/save', data, (response) => {
            console.log(response);
        });

    };

    var save_activities = () => {
        var form = $('DIV#box_history');
        var time = $(form).find('INPUT#start_time').val().trim();
        var data = {
            'student_id': student_id,
            'act_type': $(form).find('SELECT#act_type').val(),
            'start_time': time == '' ? null : moment(time).format("YYYY-MM-DD HH:mm:ss"),
            'note': $(form).find('INPUT#note').val().trim()
        }
        
        post('/api/student/save-activity', data, (response) => {
            get_activities();
        });

        $(form).find('SELECT#act_type').val('');
        $(form).find('INPUT#start_time').val('');
        $(form).find('INPUT#note').val('');

    };

    var save = () => {
        save_profile();
    }

    var set_primary_parent = (parent) => {
        student_data.profile.parent = parent;
        fill_profile(student_data.profile);
        fill_parent_list(student_data.parents);
        tab_activate($(tab_col).find('a[data-tab="profile"]'));
    }

    var init = () => {
        student_id = get_url_param('student_id');
        if (student_id == null) {
            console.log('NO DATA');
            return false;
        }

        $('FORM#frmStudent').find('INPUT#student_id').val(student_id);

        var form = $('FORM#frmStudent');
        for(var i = 1; i <= 6; i++) {
            var opt = $('<option></option>', {text:activity_types[i], value:i});
            $(form).find('SELECT#act_type').append(opt);
        }

        get_student_profile(() => {
            get_activities();
            get_parent_list();
            get_exam_results();
            get_teacher_reports();
            get_payment_history();
            get_attendance_history();
        });
    }

    require('./student_detail_modal.js');
    init();
});