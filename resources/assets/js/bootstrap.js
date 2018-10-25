try {
    window.$ = window.jQuery = require('jquery');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status === 403) {
                var msg = "message: " + jqXHR.responseJSON.message + "\n" + "code: " + jqXHR.responseJSON.code;
                console.log(msg);
            }else {
                console.log(jqXHR);
            }
        }
    });
    
    require('./student.js');
    require('./timetable.js');
    require('./rollcall.js');
    require('./holiday.js');
    require('./course.js');
    require('./classes.js');
    require('./examination.js');
    require('./staff.js');
    require('./teacher.js');
    require('./invoice.js');
    require('./branch.js');
    require('bootstrap');
} catch (e) {
    console.log(e);
}