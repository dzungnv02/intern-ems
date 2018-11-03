try {
    window.$ = window.jQuery = require('jquery');

    require('jquery-validation');

    var user_info = {
        id: $('meta[name="user-id"]').attr('content'),
        name: $('meta[name="user-name"]').attr('content'),
        email: $('meta[name="user-email"]').attr('content'),
        branch: $('meta[name="user-branch_id"]').attr('content')
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'AUTH-USER': JSON.stringify(user_info)
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