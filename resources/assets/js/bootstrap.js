try {
    // 
    window.$ = window.jQuery = require('jquery');
    window.numeral = require('numeral');

    require('bootstrap');
    require('jquery-validation');
    require('daterangepicker');
    require('datatables.net');
    require('datatables.net-buttons')(window.$);
    require('bootstrap-datepicker');

    var user_info = {
        id: $('meta[name="user-id"]').attr('content'),
        name: btoa($('meta[name="user-name"]').attr('content')),
        email: $('meta[name="user-email"]').attr('content'),
        branch: $('meta[name="user-branch_id"]').attr('content'),
        role: $('meta[name="user-role"]').attr('content')
    }
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'AUTH-USER': JSON.stringify(user_info)
        },
        cache: false,
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
            if (jqXHR.status === 403) {
                var msg = "message: " + jqXHR.responseJSON.message + "\n" + "code: " + jqXHR.responseJSON.code;
                console.log(msg);
            }else {
                console.log(jqXHR);
            }
        }
    });

    require('./student');
    require('./student_detail');
    require('./timetable');
    require('./rollcall');
    require('./holiday');
    require('./classes');
    require('./examination');
    require('./staff');
    require('./teacher');
    require('./invoice');
    require('./branch');
    require('./weeklyschedule');
    
} catch (e) {
    console.log(e);
}