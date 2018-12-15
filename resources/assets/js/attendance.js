$(function () {
    var contaner = $('DIV#attendance-modal');
    var get_attendance_list = (class_id, callback)  => {
        $.ajax({
            url: '/api/class/attendance?class_id=' + class_id,
            dataType: 'json',
            contentType: 'application/json',
            success: (response) => {
                if (callback != undefined && response.code == 1) {
                    callback(response.data);
                }
                console.log('response', response);
            }
        });
    }

    var render_attendance_table = (list) => {
        var list_container = $(contaner).find('TABLE#attendance-table tbody');
        if (list.length > 0) {
            for (var i = 0; i < list.length; i++) {
                var tr = $('<tr></tr>');
                var item = list[i];
                var td_no = $('<th></th>', {text: i + 1});
                $(tr).append(td_no);
                for (var attr in item) {
                    if (attr == 'id') continue; 
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
            }
        }
    }

    var open_attendance = (class_id) => {
        $(attendance_modal).modal('show');
    }



    // get_attendance_list(11, (list) => {
    //     render_attendance_table(list);
    // });
});