$(function () {
    if ($('UL#invoice_tabs').length == 0) return false;

    var form = $('FORM#frmTutorFee');

    var tab_headers = $('UL.nav#invoice_tabs');
    var tab_contents = $('DIV.tab-content');
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

    var get_student_list = () => {
        $(student_id).empty();
        $(class_id).empty();
        $.ajax('/invoice/student-list', {
            type: 'GET',
            contentType: 'application/json',
            success: function (response) {
                if (response.code == 0) {
                    if (response.data.list.length > 0) {
                        for (var i = 0; i < response.data.list.length; i++) {
                            var student = response.data.list[i];
                            var display_text = (student.student_code ? 'Code: <span class="text-bold">' + student.student_code + '</span> - ' : 'NO CODE - ') + student.name;
                            var opt = $('<option></option>', {
                                value: student.id,
                                html: display_text
                            });
                            $(student_id).append(opt);
                        }
                    }
                }
            }
        });
    }

    var get_class_list = (student_id) => {
        $(class_id).empty();
        $.ajax('/invoice/class-list?student_id=' + student_id, {
            type: 'GET',
            contentType: 'application/json',
            success: function (response) {
                if (response.code == 0) {
                    if (response.data.list.length > 0) {
                        for (var i = 0; i < response.data.list.length; i++) {
                            var cls = response.data.list[i];
                            var opt = $('<option></option>', {
                                value: cls.id,
                                html: cls.name
                            });
                            $(class_id).append(opt);
                        }
                        $($(class_id).find('OPTION')[0]).attr('selected', 'selected');
                        toggle_tuition_form(true);
                    } else {
                        var opt = $('<option></option>', {
                            value: 0,
                            html: 'Chưa có lớp'
                        });
                        $(class_id).append(opt);
                        $($(class_id).find('OPTION')[0]).attr('selected', 'selected');
                        toggle_tuition_form(false);
                    }
                }
            }
        });
    }

    var tuition_fee_calculate = () => {

        var data = {
            'class_id': $(form).find('SELECT#class_id').val(),
            'start_date': $(form).find('INPUT#start_date').val(),
            'end_date': $(form).find('INPUT#end_date').val(),
            'duration': $(form).find('INPUT#duration').val(),
            'price': $(form).find('INPUT#price').val()
        }

        if (data.class_id != '' && (data.start_date != '' && (data.end_date != '' || data.duration != '')) && data.price) {
            $.ajax('/invoice/tuition_fee_calculate', {
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function (response) {
                    if (response.code == 0) {
                        var res_data = response.data;
                        if (data.duration == '') $(duration).val(res_data.duration);
                        if (data.end_date == '') $(end_date).val(res_data.end_date);
                        $(amount).val(res_data.amount);
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    }

    var toggle_tuition_form = (toggle) => {
        $(form).find('INPUT#reservation').prop('disabled', !toggle);
        $(form).find('INPUT#duration').prop('disabled', !toggle);
        $(form).find('INPUT#discount').prop('disabled', !toggle);
        $(form).find('INPUT#discount-desc').prop('disabled', !toggle);
        $(form).find('INPUT#prepaid').prop('disabled', !toggle);
        $(form).find('INPUT#amount').prop('disabled', !toggle);
        $(form).find('INPUT#payer').prop('disabled', !toggle);
    }

    var init = () => {
        $('SELECT.select2[id="student_id"]').select2();
        $('SELECT.select2[id="class_id"]').select2();
        toggle_tuition_form(false);

        $(form).find('INPUT#reservation').daterangepicker({
                opens: 'right',
                locale: daterange_locale
            },
            (start, end) => {
                $(form).find('INPUT#start_date').val(start.format('YYYY-MM-DD'));
                $(form).find('INPUT#end_date').val(end.format('YYYY-MM-DD'));
                tuition_fee_calculate();
            }
        );

        $(form).find('input#price,input#duration').on('change', (e) => {
            var value = $(e.target).val().trim();
            if( value != '' && !isNaN(value)) {
                tuition_fee_calculate();
            }
        });


        $(student_id).on('change', (e) => {
            get_class_list($(student_id).val());
        });

        get_student_list();
    };

    init();

});