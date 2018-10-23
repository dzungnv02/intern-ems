$(function () {
    var form = $('FORM#frm_invoice');
    var invoice_type = $(form).find('input#invoice_type_tuition[type="radio"]:checked').length > 0 ? 'TUITION_FEE' : 'OTHER_FEE';
    
    var invoice_type_radio = $(form).find('input[type="radio"][name="invoice_type"]');
    var duration_group = $(form).find('DIV.form-group#group_duration');
    var daterange_group = $(form).find('DIV.form-group#group_date_range');
    var amount_group = $(form).find('DIV.form-group#group_amount');
   
    var student_id = $(form).find('SELECT#student_id');
    var class_id = $(form).find('SELECT#class_id');
    var reason = $(form).find('textarea#reason');

    var start_date = $(form).find('INPUT#start_date');
    var end_date = $(form).find('INPUT#end_date');
    var duration = $(duration_group).find('input#duration');
    var amount = $(amount_group).find('input#amount');

    var init = () => {
        change_invoice_type(invoice_type);
        get_student_list();

        $(start_date).datetimepicker({
            format: 'Y-m-d',
            timepicker: false,
            minDate: '-2017-01-1',
        });

        $(end_date).datetimepicker({
            format: 'Y-m-d',
            timepicker: false,
            minDate: '-2017-01-1',
        });

        $(invoice_type_radio).on('change', (e) => {
            change_invoice_type($(e.target).val());
        });

        $(student_id).on('change', (e) => {
            get_class_list($(student_id).val());
        })

        $(class_id).on('change', (e) => {
            tuition_fee_calculate();
        });

        $(start_date).on('change', (e) => {
            tuition_fee_calculate();
        });

        $(end_date).on('change', (e) => {
            $(duration).val('');
            tuition_fee_calculate();
            
        });

        $(duration).on('change', (e) => {
            tuition_fee_calculate();
        });   
    };

    var change_invoice_type = (invoice_type) => {
        if (invoice_type != 'TUITION_FEE') {
            $(duration_group).css('display', 'none');
            $(daterange_group).css('display', 'none');
            $(amount_group).find('input#amount').attr('readonly', false);        
            $(reason).val('');
        }
        else {
            $(duration_group).css('display', '');
            $(daterange_group).css('display', '');
            $(amount_group).find('input#amount').attr('readonly', true);
            $(reason).val('Đóng học phí');
            tuition_fee_calculate();
        }
    }

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
                            var opt = $('<option></option>', {value: student.id, text:student.name});
                            $(student_id).append(opt);
                        }
                    }
                }
                
            }
        });       
    }

    var get_class_list = (student_id) => {
        $.ajax('/invoice/class-list?student_id=' + student_id, {
            type: 'GET',
            contentType: 'application/json',
            success: function (response) {
                if (response.code == 0) {
                    if (response.data.list.length > 0) {
                        for (var i = 0; i < response.data.list.length; i++) {
                            var cls = response.data.list[i];
                            var opt = $('<option></option>', {value: cls.id, text: cls.name});
                            $(class_id).append(opt);
                        }

                        $($(class_id).find('OPTION')[0]).attr('selected', 'selected');
                    }
                }
                
            }
        });
    }

    var tuition_fee_calculate = () => {
        
        var data = {
            'class_id': $(class_id).val(),
            'start_date':$(start_date).val(),
            'end_date':$(end_date).val(),
            'duration' : $(duration).val()
        }

        if (data.class_id != '' && (data.start_date != '' && (data.end_date != '' || data.duration != ''))) {
            $.ajax('/invoice/tuition_fee_calculate', {
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function (response) {
                    if (response.code == 0) {
                        var res_data = response.data;
                        if (data.duration == '' ) $(duration).val(res_data.duration);
                        if (data.end_date == '' ) $(end_date).val(res_data.end_date);
                        $(amount).val(res_data.amount);
                    }
                    else {
                        alert(response.message);
                    }
                }
            });
        }
           

    }

    init();
});