$(function () {
    var table_schedule = $('TABLE#teacher-weekly-schedule');
    if (table_schedule.length > 0) {
        var form = $('FORM#frmReport');
        var teacher_list = null;
        var curr_page = 1;
        var total_page = 1;
        var teacher_per_page = 2;
        var offset = 0;
        var curr_teachers = [];
        var classes = [];
        var start = '2018-11-01';
        var end = '2018-11-30';
        var bootpag = null;

        var init = () => {
            get_teacher_list((data) => {
                paginate(data);
            });
        };

        var render_table = (list) => {
            $(table_schedule).find('thead tr#weekdays th:not(:first-child)').prop('colspan', curr_teachers.length);
            $(table_schedule).find('thead tr#teacher_header').remove();
            $(table_schedule).find('tbody').empty();

            var teachers_row = $('<tr></tr>', {
                id: 'teacher_header'
            }).append($('<td></td>'));

            for (var wd = 1; wd <= 7; wd++) {
                for (var t = 0; t < curr_teachers.length; t++) {
                    var td = $('<td></td>', {
                        text: curr_teachers[t].name,
                        'style': 'text-align:center'
                    });
                    $(teachers_row).append(td);
                }
            }

            for (var hour in list) {
                var tr = $('<tr></tr>');
                var hour_cell = $('<th></th>', {
                    class: 'schedule_hour',
                    'style': 'text-align:center',
                    text: hour
                });

                $(tr).append(hour_cell);
                var schedule_obj = list[hour];
                for (var wd in schedule_obj) {
                    for (var tid in schedule_obj[wd]) {
                        var content = '';
                        var style = '';
                        if (schedule_obj[wd][tid] != '') {
                            for (var c in classes) {
                                if (classes[c].id == schedule_obj[wd][tid]) {
                                    content = classes[c].name;
                                    console.log(classes[c]);
                                    style = 'background-color:yellow;text-align:center';
                                    break;
                                }
                            }
                        }
                        //var cell_id = 
                        var td = $('<td></td>', {
                            id: '',
                            class: 'schedule_content',
                            text: content,
                            'style': style
                        });

                        $(tr).append(td);
                    }
                }
                $(table_schedule).find('tbody').append(tr);
            }


            $(table_schedule).find('thead').append(teachers_row);
        }

        var get_schedule_list = (ids, callback) => {
            var params = ids.join('&teachers[]=');
            var end_point = 'api/teacher_weekly_time_table?start='+start+'&end='+end+'&teachers[]=' + params;

            $.ajax({
                url: end_point,
                method: 'GET',
                contentType: 'application/json',
                dataType: 'json',
                success: (response) => {
                    if (response.code == 1) {
                        classes = response.classes;
                        callback(response.data)
                    }
                }
            });
        }

        var get_teacher_list = (callback) => {
            $.ajax({
                url: '/api/list-teachers',
                method: 'GET',
                dataType: 'json',
                contentType: 'application/json',
                success: (response) => {
                    if (response.code == 1) {
                        teacher_list = response.data;
                        if (callback != undefined) callback(teacher_list);
                    }
                }
            });
        }

        var paginate = (teacher_list) => {
            if (teacher_list == null) return '';
            total_page = Math.ceil(teacher_list.length / teacher_per_page);

            if (curr_page == 1) {
                offset = 0;
            }

            bootpag = $('.paginate').bootpag({
                total: total_page,
                next: 'Sau',
                prev: 'Trước',
            }).on("page", function (event, num) {
                curr_page = num;
                if (num == 1) {
                    offset = 0;
                } else {
                    offset = teacher_per_page * (num - 1);
                }

                curr_teachers = teacher_list.slice(offset, offset + teacher_per_page);
                var ids = [];

                for (var i = 0; i < curr_teachers.length; i++) {
                    ids[i] = curr_teachers[i].id;
                }
                get_schedule_list(ids, render_table);

            });

            bootpag.trigger('page', curr_page);
        }

        var refresh_schedule = () => {
            start = $(form).find('INPUT#start').val();
            end = $(form).find('INPUT#end').val();
            if (start != '' && end != '') {
                bootpag.unbind('page');
                init();
            }
        }

        $(form).find('BUTTON#btnRefresh').on('click', (e) => {
            refresh_schedule();
        });

        init();
    }
});