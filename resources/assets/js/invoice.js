$(function () {
    if ($('UL#invoice_tabs').length == 0) return false;

    var frmTutorFee = $('FORM#frmTutorFee');
    var frmOtherFee = $('FORM#frmOtherFee');

    var tab_headers = $('UL.nav#invoice_tabs');
    var tab_contents = $('DIV.tab-content');
    var tab_col = $(tab_headers).find('LI');
    var actived_tab = $(tab_contents).find("DIV[role='tabpanel'].active");
    var invoice_list_table = null;
    var students = {};

    var student_id = $('FORM#frmTutorFee SELECT.select2[id="student_id"]');
    var class_id = $('FORM#frmTutorFee SELECT.select2[id="class_id"]').select2();
    var prepaid = $('FORM#frmTutorFee INPUT#prepaid');

    var student_other_id = $('FORM#frmOtherFee SELECT.select2[id="student_id"]').select2();
    var class_other_id =$('FORM#frmOtherFee SELECT.select2[id="class_id"]').select2();

    var tab_activate = (target) => {
        $(tab_col).removeClass('active');
        $(target).parent().addClass('active');
        $(tab_contents).find("DIV[role='tabpanel']").removeClass('active');
        actived_tab = $(tab_contents).find("DIV[role='tabpanel']#" + $(target).data('tab')).addClass('active');

        if ($(actived_tab).prop('id') == 'invoicelist-tab') {
            $('BUTTON#btnSaveInvoice').css('display', 'none');
            $('BUTTON#btnPrintInvoice').css('display', 'none');
            $('BUTTON#btnCreateInvoice').css('display', 'inline');
        } else {
            $('BUTTON#btnSaveInvoice').css('display', 'inline');
            $('BUTTON#btnPrintInvoice').css('display', 'inline');
            $('BUTTON#btnCreateInvoice').css('display', 'none');
        }
    }

    $(tab_headers).find('A').bind('click', (e) => {
        if ($(e.target).parent().hasClass('disabled')) {
            return;
        }
        tab_activate(e.target);
        e.preventDefault();
        e.stopPropagation();
    });

    var get_student_list = (callback, student_target, class_target) => {
        $(student_id).empty();
        $(class_id).empty();

        $(student_other_id).empty();
        $(class_other_id).empty();

        $.ajax('/invoice/student-list', {
            type: 'GET',
            contentType: 'application/json',
            success: function (response) {
                if (response.code == 0) {
                    var first_val = null;
                    if (response.data.list.length > 0) {
                        for (var i = 0; i < response.data.list.length; i++) {
                            var student = response.data.list[i];
                            
                            first_val = (first_val == null) ? student.id : first_val;
                            
                            students[student.id] = student;
                            
                            var display_text = (student.student_code ? 'Code: <span class="text-bold">' + student.student_code + '</span> - ' : 'NO CODE - ') + student.name;
                            var opt = $('<option></option>', {
                                value: student.id,
                                html: display_text
                            });
                            $(student_target).append(opt);
                        }
                        if (callback != undefined) {
                            callback(first_val, class_target);
                        }
                    }
                }
            }
        });
    }

    var get_class_list = (std_id, target, callback) => {
        $(target).empty();
        $.ajax('/invoice/class-list?student_id=' + std_id, {
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
                            $(target).append(opt);
                        }
                        $(frmTutorFee).find('INPUT#price').val(numeral(response.data.list[0].price).format('0,0'));
                        toggle_tuition_frmTutorFee(true);
                    } else {
                        var opt = $('<option></option>', {
                            value: 0,
                            html: 'Chưa có lớp'
                        });
                        $(target).append(opt);
                    }
                    $($(target).find('OPTION')[0]).attr('selected', 'selected');
                    if (callback != undefined) {
                        callback();
                    }
                }
            }
        });
    }

    var get_parent_list = (student_id, callback) => {
        $(frmTutorFee).find('INPUT#payer').val('');
        $.get('/api/parent/list', {
            student_id: student_id
        }, (response) => {
            for(var i = 0; i < response.data.length; i++) {
                if (response.data[i].id === students[student_id].parent_id) {
                    $(frmTutorFee).find('INPUT#payer').val(response.data[i].fullname);
                }
            }
            if (callback != undefined) callback();
        });
    }

    var tuition_fee_calculate = () => {

        var data = {
            'class_id': $(frmTutorFee).find('SELECT#class_id').val(),
            'start_date': $(frmTutorFee).find('INPUT#start_date').val(),
            'end_date': $(frmTutorFee).find('INPUT#end_date').val(),
            'duration': $(frmTutorFee).find('INPUT#duration').val(),
            'price': numeral($(frmTutorFee).find('INPUT#price').val()).value(),
            'discount': $(frmTutorFee).find('INPUT#discount').val(),
            'prepaid': numeral($(frmTutorFee).find('INPUT#prepaid').val()).value()
        }

        if (data.class_id != '' && (data.start_date != '' && (data.end_date != '' || data.duration != '')) && data.price) {
            $.ajax('/invoice/tuition_fee_calculate', {
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function (response) {
                    if (response.code == 0) {
                        var res_data = response.data;
                        if (data.duration == '') $(frmTutorFee).find('INPUT#duration').val(res_data.duration);
                        if (data.end_date == '') $(frmTutorFee).find('INPUT#end_date').val(res_data.end_date);
                        $(amount).val(numeral(res_data.amount).format('0,0'));
                        $(amount).change();
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    }

    var toggle_tuition_frmTutorFee = (toggle) => {
        $(frmTutorFee).find('INPUT#reservation').prop('disabled', !toggle);
        $(frmTutorFee).find('INPUT#duration').prop('disabled', !toggle);
        $(frmTutorFee).find('INPUT#discount').prop('disabled', !toggle);
        $(frmTutorFee).find('INPUT#discount-desc').prop('disabled', !toggle);
        $(frmTutorFee).find('INPUT#prepaid').prop('disabled', !toggle);
        $(frmTutorFee).find('INPUT#amount').prop('disabled', !toggle);
    }

    var save_tutor_invoice = (callback, form_activated, data) => {

        $(form_activated).find('.help-block').hide();
        $(form_activated).find('.has-error').removeClass('has-error');

        if ($(form_activated).prop('id') == 'frmTutorFee') {
            if (!tutorfee_validate(data)) return;
        }
        else if ($(form_activated).prop('id') == 'frmOtherFee') {
            if (!otherfee_validate(data)) return;
        }

        $.ajax('/invoice/save', {
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (response) {
                $(form_activated).find('input#iid').val(response.id);
                console.log(response);
                if (callback != undefined) {
                    callback(response.id);
                }
            }
        });
    }

    var print_invoice = (invoice_id) => {
        $.ajax({
            url: '/invoice/print/' + invoice_id + '/print',
            type: 'GET',
            dataType: 'html',
            success: function (response) {
                var w = window.open();
                w.document.write(response);
                setTimeout(() => {
                    w.print();
                    w.close();
                }, 1000);
            }
        });
    };

    var view_invoice = (invoice_id) => {
        $.ajax({
            url: '/invoice/print/' + invoice_id + '/view',
            type: 'GET',
            dataType: 'html',
            success: function (response) {
                $('DIV.modal#view-invoice').find('DIV.modal-body').empty(); 
                $('DIV.modal#view-invoice').find('DIV.modal-body').append(response);
                $('DIV.modal#view-invoice').modal('show');
            }
        });
    }

    var tutorfee_validate = (data) => {
        var is_valided = true;
        var container = null;
        if (data.payer == '') {
            container = $(frmTutorFee).find('INPUT#payer').parent();
            $(container).addClass('has-error');
            $(container).find('.help-block').html('Hãy nhập Người đóng tiền').show();
            is_valided = false;
        }

        if (data.price != '' && isNaN(data.price)) {
            container = $(frmTutorFee).find('INPUT#price').parent().parent();
            $(container).addClass('has-error');
            $(container).find('.help-block').html('Hãy nhập giá trị là số cho Học phí mỗi buổi học').show();
            is_valided = false;
        }

        if (data.discount != '' && isNaN(data.discount)) {
            container = $(frmTutorFee).find('INPUT#discount').parent();
            $(container).addClass('has-error');
            $(container).find('.help-block').html('Hãy nhập giá trị là số nhỏ hơn hoặc bằng 100').show();
            is_valided = false;
        }

        if (data.discount != '' && !isNaN(data.discount)) {
            if (data.discount_desc == '') {
                container = $(frmTutorFee).find('INPUT#discount-desc').parent();
                $(container).addClass('has-error');
                $(container).find('.help-block').html('Hãy nhập Lý do chiết khấu').show();
                is_valided = false;
            }
        }

        return is_valided;
    }

    var otherfee_validate = (data) => {
        console.log('Validate other', data);
        var is_valided = true;
        var container = null;
        if (data.payer == '') {
            container = $(frmOtherFee).find('INPUT#payer').parent();
            $(container).addClass('has-error');
            $(container).find('.help-block').html('Hãy nhập Người đóng tiền').show();
            is_valided = false;
        }

        if (data.reason == '') {
            container = $(frmOtherFee).find('textarea#reason').parent();
            $(container).addClass('has-error');
            $(container).find('.help-block').html('Hãy nhập Lý do nộp').show();
            is_valided = false;
        }

        if (data.amount == '' || data.amount == null) {
            console.log($(frmOtherFee).find('INPUT#amount'));
            container = $(frmOtherFee).find('INPUT#amount').parent().parent();
            $(container).addClass('has-error');
            $(container).find('.help-block').html('Hãy nhập Tổng số tiền').show();
            is_valided = false;
        }

        return is_valided;
    }

    var get_last_tutor_invoice_duration = (student_id, class_id, callback) => {
        $.ajax({
            url: '/invoice/get-last-invoice/' + student_id + '/' + class_id
        });
    }

    var invoice_list_init = () => {
        if ($.fn.dataTable.isDataTable('TABLE#invoice-list')) {
            invoice_list_table = $('TABLE#invoice-list').DataTable();
        } else {
            var buttons = '<button title="In phiếu thu" class="btn btn-primary print-invoice"><i class="fa fa-print" aria-hidden="true"></i></button>\
                            <button title="Xem" class="btn btn-info view-invoice"><i class="fa fa-eye" aria-hidden="true"></i></button>';
            invoice_list_table = $('TABLE#invoice-list').DataTable({
                language: datatable_language,
                "columnDefs": [{
                    targets: [7],
                    "data": null,
                    "visible": true,
                    "defaultContent": buttons
                }],
                ajax: {
                    url: 'invoice/list',
                    dataSrc: 'data.list',
                },
                "order": [
                    [1, 'asc']
                ],
                columns: [{
                        data: 'invoice_number',
                        name: 'invoice_number'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: (data, type, row, meta) => {
                           return moment(data).format("YYYY-MM-DD kk:mm");
                        }
                    },
                    {
                        data: 'type',
                        name: 'type',
                        render: (data, type, row, meta) => {
                           return data == 1 ? 'Học phí':'Thu khác';
                        }
                    },
                    {
                        data: 'std_name',
                        name: 'std_name'
                    },
                    {
                        data: 'c_name',
                        name: 'c_name'
                    },
                    {
                        data: 'payer',
                        name: 'payer'
                    },
                    {
                        data: 'invoice_status',
                        name: 'invoice_status',
                        render: (data, type, row, meta) => {
                            var status = '';
                            switch (data) {
                                case 0:
                                    status = 'Lưu tạm thời';
                                break;
                                case 1:
                                    status = 'Đã in';
                                break;
                                case 2:
                                    status = 'Đã in lại';
                                break;
                                default:
                                break;
                            }
                            
                            return status;
                        }
                    },
                    {
                        data: null
                    }
                ]
            });

            invoice_list_table.on('click', 'button.print-invoice', function () {
                var data = invoice_list_table.row($(this).parents('tr')).data();
                print_invoice(data.id);
                setTimeout(() => {
                    invoice_list_table.ajax.reload()
                }, 500);
            });

            invoice_list_table.on('click', 'button.view-invoice', function () {
                var data = invoice_list_table.row($(this).parents('tr')).data();
                view_invoice(data.id);
            });
        }
    };

    var init = () => {
        
        $(student_id).select2();
        $(class_id).select2();

        $(student_other_id).select2();
        $(class_other_id).select2();

        $('.help-block').hide();
        $('.has-error').removeClass('has-error');
        toggle_tuition_frmTutorFee(false);

        tab_activate($(tab_headers).find('A[data-tab="invoicelist-tab"]')[0]);

        $(frmTutorFee).find('INPUT#reservation').daterangepicker({
                opens: 'right',
                locale: daterange_locale
            },
            (start, end) => {
                $(frmTutorFee).find('INPUT#start_date').val(start.format('YYYY-MM-DD'));
                $(frmTutorFee).find('INPUT#end_date').val(end.format('YYYY-MM-DD'));
                tuition_fee_calculate();
            }
        );

        $(frmTutorFee).find('input#price,input#duration,input#discount,input#prepaid').on('blur', (e) => {
            var value = $(e.target).val().trim();
            if (e.target.id == 'price' || e.target.id == 'prepaid') {
                $(e.target).val(numeral(value).format('0,0'));
            }
            tuition_fee_calculate();
        });

        $(frmTutorFee).find('input#amount').on('change', (e) => {            
            if ($(e.target).val() != '') {
                $('BUTTON#btnSaveInvoice').prop('disabled', false);
                $('BUTTON#btnPrintInvoice').prop('disabled', false);
            }
        });

        $(frmOtherFee).find('input#amount').on('blur', (e) => {
            var value = $(e.target).val().trim();
            if (value != '') {
                $(e.target).val(numeral(value).format('0,0'));
                var container = $(e.target).parent().parent();
                $(container).removeClass('has-error');
                $(container).find('.help-block').html('Hãy nhập Tổng số tiền').hide();
            }
        });

        //$(prepaid).on('');

        $(class_id).on('change', (e) => {
            console.log(e.target);
        });

        $(student_id).on('change', (e) => {
            get_parent_list($(student_id).val(), () => {
                get_class_list($(student_other_id).val(), $(class_id),() => {
                    toggle_tuition_frmTutorFee(false);
                });
            });
        });

        $(student_other_id).on('change', (e) => {
            get_class_list($(student_other_id).val(), $(class_other_id),() => {
                console.log('Other fee');
            });
        });

        $('BUTTON#btnSaveInvoice').on('click', (e) => {
            var actived_tab_id = $(actived_tab).prop('id');
            var data = {};
            if (actived_tab_id.indexOf('tutorfee') == 0) {
                data = {
                    "type": 1,
                    "student_id": $(frmTutorFee).find('SELECT#student_id').val(),
                    "class_id": $(frmTutorFee).find('SELECT#class_id').val(),
                    "price": numeral($(frmTutorFee).find('INPUT#price').val()).value(),
                    "start_date": $(frmTutorFee).find('INPUT#start_date').val(),
                    "end_date": $(frmTutorFee).find('INPUT#end_date').val(),
                    "duration": $(frmTutorFee).find('INPUT#duration').val(),
                    "payer": $(frmTutorFee).find('INPUT#payer').val(),
                    "prepaid": numeral($(frmTutorFee).find('INPUT#prepaid').val()).value(),
                    "amount": numeral($(frmTutorFee).find('INPUT#amount').val()).value(),
                    "discount": $(frmTutorFee).find('INPUT#discount').val(),
                    "discount_desc": $(frmTutorFee).find('INPUT#discount_desc').val(),
                    "invoice_status": 0,
                    "currency": 'VND',
                };

                save_tutor_invoice(() => {
                    tab_activate($(tab_headers).find('A[data-tab="invoicelist-tab"]')[0]);
                    invoice_list_table.ajax.reload();
                }, frmTutorFee, data);
            }
            else if (actived_tab_id.indexOf('otherfee') == 0){
                data = {
                    "type": 2,
                    "student_id": $(frmOtherFee).find('SELECT#student_id').val(),
                    "class_id": $(frmOtherFee).find('SELECT#class_id').val(),
                    "payer": $(frmOtherFee).find('INPUT#payer').val(),
                    "reason": $(frmOtherFee).find('TEXTAREA#reason').val(),
                    "amount": numeral($(frmOtherFee).find('INPUT#amount').val()).value(),
                    "invoice_status": 0,
                    "currency": 'VND',
                }

                save_tutor_invoice(() => {
                    tab_activate($(tab_headers).find('A[data-tab="invoicelist-tab"]')[0]);
                    invoice_list_table.ajax.reload();
                }, frmOtherFee, data);
            }

            $(frmTutorFee)[0].reset();
            $(frmOtherFee)[0].reset();
        });

        $('BUTTON#btnPrintInvoice').on('click', (e) => {
            var actived_tab_id = $(actived_tab).prop('id');
            if (actived_tab_id.indexOf('tutorfee') == 0) {
                if ($(frmTutorFee).find('input#iid').val() == '') {
                    data = {
                        "type": 1,
                        "student_id": $(frmTutorFee).find('SELECT#student_id').val(),
                        "class_id": $(frmTutorFee).find('SELECT#class_id').val(),
                        "price": numeral($(frmTutorFee).find('INPUT#price').val()).value(),
                        "start_date": $(frmTutorFee).find('INPUT#start_date').val(),
                        "end_date": $(frmTutorFee).find('INPUT#end_date').val(),
                        "duration": $(frmTutorFee).find('INPUT#duration').val(),
                        "payer": $(frmTutorFee).find('INPUT#payer').val(),
                        "prepaid": numeral($(frmTutorFee).find('INPUT#prepaid').val()).value(),
                        "amount": numeral($(frmTutorFee).find('INPUT#amount').val()).value(),
                        "discount": $(frmTutorFee).find('INPUT#discount').val(),
                        "discount_desc": $(frmTutorFee).find('INPUT#discount_desc').val(),
                        "invoice_status": 1,
                        "currency": 'VND',
                    };

                    save_tutor_invoice((invoce_id) => {
                        print_invoice(invoce_id);
                        tab_activate($(tab_headers).find('A[data-tab="invoicelist-tab"]')[0]);
                        invoice_list_table.ajax.reload();
                    }, frmTutorFee, data);
                } else {
                    print_invoice($(frmTutorFee).find('input#iid').val());
                    tab_activate($(tab_headers).find('A[data-tab="invoicelist-tab"]')[0]);
                }
            }
            else if (actived_tab_id.indexOf('otherfee') == 0){
                if ($(frmOtherFee).find('input#iid').val() == '') {
                    data = {
                        "type": 2,
                        "student_id": $(frmOtherFee).find('SELECT#student_id').val(),
                        "class_id": $(frmOtherFee).find('SELECT#class_id').val(),
                        "payer": $(frmOtherFee).find('INPUT#payer').val(),
                        "reason": $(frmOtherFee).find('TEXTAREA#reason').val(),
                        "amount": numeral($(frmOtherFee).find('INPUT#amount').val()).value(),
                        "invoice_status": 1,
                        "currency": 'VND',
                    }

                    save_tutor_invoice((invoce_id) => {
                        print_invoice(invoce_id);
                        tab_activate($(tab_headers).find('A[data-tab="invoicelist-tab"]')[0]);
                        invoice_list_table.ajax.reload();
                    }, frmOtherFee, data);
                }
                else {
                    print_invoice($(frmOtherFee).find('input#iid').val());
                    tab_activate($(tab_headers).find('A[data-tab="invoicelist-tab"]')[0]);
                }
            }

            $(frmTutorFee)[0].reset();
            $(frmOtherFee)[0].reset();
        });

        $('BUTTON#btnCreateInvoice').on('click', () => {
            $(tab_headers).find('LI').removeClass('disabled');
            tab_activate($(tab_headers).find('A[data-tab="tutorfee-tab"]')[0]);
        });

        invoice_list_init();

        get_student_list((std_id, class_target) => {
            get_class_list(std_id, class_target);
        }, $('SELECT.select2[id="student_id"]') ,$('SELECT.select2[id="class_id"]'));
    };

    init();

});