$(function () {
    if ($('UL#invoice_tabs').length == 0) return false;

    var frmTutorFee = $('FORM#frmTutorFee');
    var frmOtherFee = $('FORM#frmOtherFee');

    var select2_tutor_studentid = null;
    var select2_tutor_classid = null;

    var select2_other_studentid = null;
    var select2_other_classid = null;

    var tab_headers = $('UL.nav#invoice_tabs');
    var tab_contents = $('DIV.tab-content');
    var tab_col = $(tab_headers).find('LI');
    var actived_tab = $(tab_contents).find("DIV[role='tabpanel'].active");
    var invoice_list_table = null;
    var students = {};

    var student_id = $('FORM#frmTutorFee SELECT.select2[id="student_id"]');
    var class_id = $('FORM#frmTutorFee SELECT.select2[id="class_id"]').select2();

    var student_other_id = $('FORM#frmOtherFee SELECT.select2[id="student_id"]').select2();
    var class_other_id = $('FORM#frmOtherFee SELECT.select2[id="class_id"]').select2();

    var tab_activate = (target) => {
        $(tab_col).removeClass('active');
        $(target).parent().addClass('active');
        $(tab_contents).find("DIV[role='tabpanel']").removeClass('active');
        actived_tab = $(tab_contents).find("DIV[role='tabpanel']#" + $(target).data('tab')).addClass('active');

        if ($(actived_tab).prop('id') == 'invoicelist-tab') {
            $('BUTTON#btnSaveInvoice').css('display', 'none');
            $('BUTTON#btnPrintInvoice').css('display', 'none');
            $('BUTTON#btnCreateInvoice').css('display', 'inline');
            $(frmTutorFee)[0].reset();
            $(frmOtherFee)[0].reset();
            $(tab_headers).find('LI').addClass('disabled');
            $(tab_headers).find('A[data-tab="invoicelist-tab"]').parent().removeClass('disabled');
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

    var get_class_list = (std_id, target, callback, form) => {
        if (form === undefined) form = frmTutorFee;
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
                        if (form === frmTutorFee) {
                            $(form).find('INPUT#price').val(numeral(response.data.list[0].price).format('0,0'));
                            toggle_tuition_frmTutorFee(true);
                        }

                    } else {
                        var opt = $('<option></option>', {
                            value: 0,
                            html: 'Chưa có lớp'
                        });
                        $(target).append(opt);
                        if (form === frmTutorFee) {
                            toggle_tuition_frmTutorFee(false);
                        }
                    }
                    $($(target).find('OPTION')[0]).attr('selected', 'selected');
                    if (callback != undefined) {
                        callback();
                    }
                }
            }
        });
    }

    var get_parent_list = (student_id, callback, form) => {
        if (form === undefined) form = frmTutorFee;
        $(form).find('INPUT#payer').val('');
        $.get('/api/parent/list', {
            student_id: student_id
        }, (response) => {
            for (var i = 0; i < response.data.length; i++) {
                if (response.data[i].id === students[student_id].parent_id) {
                    $(form).find('INPUT#payer').val(response.data[i].fullname);
                    $(form).find('INPUT#payer').trigger("change");
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
            'discount': numeral($(frmTutorFee).find('INPUT#discount').val()).value(),
            'discount_type': $(frmTutorFee).find('INPUT#discount-type')[0].checked === false ? 'p':'c', 
            'prepaid': numeral($(frmTutorFee).find('INPUT#prepaid').val()).value()
        }

        if (data.class_id != '' && (data.start_date != '' && (data.end_date != '' || data.duration != '')) && data.price) {
            $.ajax('/invoice/tuition_fee_calculate', {
                method: "POST",
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function (response) {
                    if (response.code == 0) {
                        var res_data = response.data;
                        if (data.duration == '') {
                            $(frmTutorFee).find('INPUT#duration').val(res_data.duration);
                        }
                        else if (data.end_date == '') {
                            $(frmTutorFee).find('INPUT#end_date').val(res_data.end_date);
                        }
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
        $(frmTutorFee).find('INPUT#discount-type').prop('disabled', !toggle);
        $(frmTutorFee).find('INPUT#prepaid').prop('disabled', !toggle);
        $(frmTutorFee).find('INPUT#amount').prop('disabled', !toggle);
    }

    var save_invoice = (callback, form_activated, data) => {
        $(form_activated).find('.help-block').hide();
        $(form_activated).find('.has-error').removeClass('has-error');

        if ($(form_activated).prop('id') == 'frmTutorFee') {
            if (!tutorfee_validate(data)) return;
        }
        else if ($(form_activated).prop('id') == 'frmOtherFee') {
            if (!otherfee_validate(data)) return;
        }

        $.ajax('/invoice/save', {
            method: "POST",
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (response) {
                $(form_activated).find('input#iid').val(response.id);
                if (callback != undefined) {
                    callback(response.id);
                }
            },
            error: (xhr, status, err) => {
                alert(err);
            }
        });
    }

    var print_invoice = (invoice_id, callback) => {
        $.ajax({
            url: '/invoice/print/' + invoice_id + '/print',
            type: 'GET',
            dataType: 'html',
            success: (response) => {
                var w = window.open();
                w.document.write(response);
                setTimeout(() => {
                    w.print();
                    w.close();
                }, 1000);
                if (callback != undefined) {
                    callback();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var response = JSON.parse(jqXHR.responseText)
                alert(response.message);
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
        $(frmTutorFee).find('.has-error').removeClass('has-error');
        $(frmTutorFee).find('.help-block').hide();

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
            container = $(frmTutorFee).find('INPUT#discount').parent().parent();
            $(container).addClass('has-error');
            if ($(frmTutorFee).find('INPUT#discount-type')[0].checked === false) {
                $(container).find('.help-block').html('Hãy nhập giá trị là số nhỏ hơn hoặc bằng 100').show();
            }
            else {
                $(container).find('.help-block').html('Hãy nhập giá trị là số tiền').show();
            }
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
        $(frmOtherFee).find('.has-error').removeClass('has-error');
        $(frmOtherFee).find('.help-block').hide();

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

            invoice_list_table = $('TABLE#invoice-list').DataTable({
                language: datatable_language,
                "columnDefs": [{
                    targets: [7],
                    "data": null,
                    "visible": true,
                    "defaultContent": ''
                }],
                ajax: {
                    url: 'invoice/list',
                    dataSrc: 'data.list',
                },
                "order": [
                    [1, 'desc']
                ],
                columns: [
                    {
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
                            return data == 1 ? 'Học phí' : 'Thu khác';
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
                                    status = '<span class="label label-info">Lưu (chưa in)</span>';
                                    break;
                                case 1:
                                    status = '<span class="label label-warning">Đã in lần 1</span>';
                                    break;
                                case 2:
                                    status = '<span class="label label-warning">Đã in lần 2</span>';
                                    break;
                                case 3:
                                    status = '<span class="label label-success">Đã duyệt</span>';
                                    break;
                                case 4:
                                    status = '<span class="label label-danger">Đã huỷ</span>';
                                    break;
                                default:
                                    break;
                            }

                            return status;
                        }
                    },
                    {
                        data: null,
                        name: 'buttons',
                        render: (data, type, row, meta) => {
                            var user_info = {
                                id: $('meta[name="user-id"]').attr('content'),
                                name: btoa($('meta[name="user-name"]').attr('content')),
                                email: $('meta[name="user-email"]').attr('content'),
                                branch: $('meta[name="user-branch_id"]').attr('content'),
                                role: $('meta[name="user-role"]').attr('content')
                            }

                            var b_print = $('<button title="In phiếu thu" class="btn btn-primary print-invoice" style="margin-right: 2px;"><i class="fa fa-print" aria-hidden="true"></i></button>');
                            var b_view = $('<button title="Xem" class="btn btn-info view-invoice" style="margin-right: 2px;"><i class="fa fa-eye" aria-hidden="true"></i></button>');
                            var b_trash = $('<button title="Huỷ" class="btn btn-danger del-invoice"><i class="fa fa-trash" aria-hidden="true"></i></button>');
                            var b_accountant_locked = $('<button title="Duyệt" class="btn btn-success approve-invoice" style="margin-right: 2px;"><i class="fa fa-check-square-o" aria-hidden="true"></i></button>');

                            var content = $('<span></span>');
                            content.append(b_view, b_print, b_accountant_locked, b_trash);

                            if (row.invoice_status >= 2) {
                                $(b_print).prop('disabled', true);
                            }

                            if (row.invoice_status > 2) {
                                $(b_trash).prop('disabled', true);
                            }

                            if (user_info.role != 4) {
                                $(b_accountant_locked).css('display', 'none');
                                $('TABLE#invoice-list tbody tr td:nth-child(8)').css('width', '8%');
                            }
                            else if (row.invoice_status > 2) {
                                $(b_accountant_locked).prop('disabled', true);
                            }

                            return content.html();
                        }
                    }
                ],
                initComplete: () => {
                    var filter_bar = filter_bar_render();

                    $('DIV#invoice-list_wrapper').prepend(
                        filter_bar,
                        $('<div></div>', { class: 'row', style: 'height:20px' })
                    );
                }
            });

            invoice_list_table.on('draw.dt', () => {
                var user_role = $('meta[name="user-role"]').attr('content');
                if (user_role != 4) {
                    $('TABLE#invoice-list tbody tr td:nth-child(8)').css('width', '8%');
                }
            });

            invoice_list_table.on('click', 'button.print-invoice', function () {
                var data = invoice_list_table.row($(this).parents('tr')).data();
                print_invoice(data.id, () => {
                    setTimeout(() => {
                        invoice_list_table.ajax.reload(() => {
                            invoice_list_table.column('1:visible').order( 'desc' ).draw();
                        })
                    }, 500);
                });
            });

            invoice_list_table.on('click', 'button.view-invoice', function () {
                var data = invoice_list_table.row($(this).parents('tr')).data();
                view_invoice(data.id);
            });

            invoice_list_table.on('click', 'button.del-invoice', function () {
                var data = invoice_list_table.row($(this).parents('tr')).data();
                if (confirm('Bạn có chắc muốn huỷ hoá đơn sô ' + data.invoice_number + ' không?')) {
                    $.ajax('/invoice/delete', {
                        method: "POST",
                        contentType: 'application/json',
                        data: JSON.stringify({ "id": data.id }),
                        success: function (response) {
                            alert('Đã huỷ!');
                            invoice_list_table.column('1:visible').order( 'desc' ).draw();
                        },
                        error: (xhr, status, err) => {
                            alert(err);
                        }
                    });
                }
            });

            invoice_list_table.on('click', 'button.approve-invoice', function () {
                var data = invoice_list_table.row($(this).parents('tr')).data();
                if (confirm('Bạn có chắc muốn duyệt hoá đơn sô ' + data.invoice_number + ' không?')) {
                    $.ajax('/invoice/approve', {
                        method: "POST",
                        contentType: 'application/json',
                        data: JSON.stringify({ "id": data.id }),
                        success: function (response) {
                            alert('Đã duyệt!');
                            invoice_list_table.column('1:visible').order( 'desc' ).draw();
                        },
                        error: (xhr, status, err) => {
                            alert(err);
                        }
                    });

                }
            });

        }
    };

    var invoice_list_event_binding = () => {
        $('BUTTON#btnCreateInvoice').on('click', () => {
            frmTutorFee[0].reset();
            $(tab_headers).find('LI').removeClass('disabled');
            tab_activate($(tab_headers).find('A[data-tab="tutorfee-tab"]')[0]);
        });
    }

    var invoice_tutor_form_event_binding = () => {

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

        $(frmTutorFee).find('input#price,input#duration,input#prepaid,input#discount').on('blur', (e) => {
            var value = $(e.target).val().trim();
            if (e.target.id == 'price' ||  e.target.id == 'prepaid') {
                $(e.target).val(numeral(value).format('0,0'));
            }
            tuition_fee_calculate();
        });

        $(frmTutorFee).find('INPUT#discount-type').on('click', (e) => {
            $(frmTutorFee).find('input#discount').val('');
            $(frmTutorFee).find('input#discount').unbind('blur');
            if ($(e.target)[0].checked === true) {
                $(frmTutorFee).find('SPAN#discount-icon').text('VND');
                $(frmTutorFee).find('input#discount').on('blur', (e) => {
                    var value = $(e.target).val().trim();
                    $(e.target).val(numeral(value).format('0,0'));
                    tuition_fee_calculate();
                });
            }
            else {
                $(frmTutorFee).find('SPAN#discount-icon').text('%');
                $(frmTutorFee).find('input#discount').on('blur', (e) => {
                    tuition_fee_calculate();
                });
            }
            tuition_fee_calculate();
        })

        $(frmTutorFee).find('input#amount').on('change', (e) => {
            if ($(e.target).val() != '') {
                $('BUTTON#btnSaveInvoice').prop('disabled', false);
                $('BUTTON#btnPrintInvoice').prop('disabled', false);
            }
        });

        select2_tutor_studentid.on('change', (e) => {
            get_parent_list($(e.target).val(), () => {
                get_class_list($(e.target).val(), $(class_id));
            });
        });

        $(frmTutorFee).on('keyup change paste', 'input, select, textarea', (e) => {
            $(e.target).closest('.has-error').find('.help-block').hide();
            $(e.target).closest('.has-error').removeClass('has-error');
        });

    }

    var invoice_other_form_event_binding = () => {
        $(frmOtherFee).find('input#amount').on('blur', (e) => {
            var value = $(e.target).val().trim();
            if (value != '') {
                $(e.target).val(numeral(value).format('0,0'));
                var container = $(e.target).parent().parent();
                $(container).removeClass('has-error');
                $(container).find('.help-block').html('Hãy nhập Tổng số tiền').hide();
            }
        });

        select2_other_studentid.on('change', (e) => {
            get_parent_list($(e.target).val(), () => {
                get_class_list($(e.target).val(), $(class_other_id));
            }, frmOtherFee);
        });

        $(frmOtherFee).on('keyup change paste', 'input, select, textarea', (e) => {
            $(e.target).closest('.has-error').find('.help-block').hide();
            $(e.target).closest('.has-error').removeClass('has-error');
        });
    }

    var filter_bar_render = () => {
        var wrapper = $('<div></div>', { id: 'invoice_filter_bar', class: 'row' });

        var wrapper_title = $('<div></div>', { text: '' });

        var status_filter = $('<select></select>', { id: 'invoice-list-status-filter', class: 'form-control' });
        var date_filter = $('<input>', { id: 'invoice-list-date-filter', class: 'form-control', type: 'text', style: 'width:100px' });
        var type_filter = $('<select></select>', { id: 'invoice-list-type-filter', class: 'form-control' });

        $(status_filter).append($('<option></option>', { 'value': -1, html: '[ Tất cả trạng thái ]' }));
        var status = ['Lưu (chưa in)', 'Đã in lần 1', 'Đã in lần 2', 'Đã duyệt', 'Đã huỷ'];
        for (var i = 0; i < status.length; i++) {
            $(status_filter).append(
                $('<option></option>', { 'value': status[i], html: status[i] })
            );
        }

        $(status_filter).on('change', (e) => {
            var search_value = $(e.target).val() == -1 ? '' : $(e.target).val();
            invoice_list_table.column(6).search(search_value).draw();
        })

        $(type_filter).append($('<option></option>', { 'value': -1, html: '[ Tất cả ]' }));
        var type = ['Học phí', 'Thu khác'];
        for (var i = 0; i < type.length; i++) {
            $(type_filter).append(
                $('<option></option>', { 'value': type[i], html: type[i] })
            );
        }

        $(type_filter).on('change', (e) => {
            var search_value = $(e.target).val() == -1 ? '' : $(e.target).val();
            invoice_list_table.column(2).search(search_value).draw();
        });

        wrapper.append(
            $('<div></div>', { class: 'col-sm-3' }).append(wrapper_title),
            $('<div></div>', { class: 'col-sm-3' }).append($('<label>Loại hoá đơn: </label>').append(type_filter)),
            $('<div></div>', { class: 'col-sm-3' }).append($('<label>Trạng thái hoá đơn: </label>').append(status_filter)),
            $('<div></div>', { class: 'col-sm-3' }).append($('<label>Thời gian lập: </label>').append(date_filter))
        );

        $(date_filter).datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd'
        });

        $(date_filter).on('change', (e) => {
            var search_value = $.fn.dataTable.util.escapeRegex(
                $(e.target).val()
            );
            console.log(search_value);
            invoice_list_table.column(1).search(search_value ? '^' + search_value : '', true, false).draw();
        });

        return wrapper;
    }

    var init = () => {
        invoice_list_init();
        invoice_list_event_binding();

        $('.help-block').hide();
        $('.has-error').removeClass('has-error');
        toggle_tuition_frmTutorFee(false);

        tab_activate($(tab_headers).find('A[data-tab="invoicelist-tab"]')[0]);

        select2_tutor_studentid = $(student_id).select2();
        select2_tutor_classid = $(class_id).select2();

        select2_other_studentid = $(student_other_id).select2();
        select2_other_classid = $(class_other_id).select2();

        toggle_tuition_frmTutorFee(false);

        get_student_list((std_id, class_target) => {
            get_class_list(std_id, class_target);
        }, $('SELECT.select2[id="student_id"]'), $('SELECT.select2[id="class_id"]'));

        invoice_tutor_form_event_binding();
        invoice_other_form_event_binding();

        $('BUTTON#btnSaveInvoice, BUTTON#btnPrintInvoice').on('click', (e) => {
            var save_callback = null;
            var data = null;
            var actived_tab_id = $(actived_tab).prop('id');
            var invoice_type = actived_tab_id == 'tutorfee-tab' ? 'tutorfee' : (actived_tab_id == 'otherfee-tab' ? 'otherfee' : '');
            var form = actived_tab_id == 'tutorfee-tab' ? frmTutorFee : (actived_tab_id == 'otherfee-tab' ? frmOtherFee : null);
            if (invoice_type == 'tutorfee') {
                data = {
                    "type": 1,
                    "student_id": $(form).find('SELECT#student_id').val(),
                    "class_id": $(form).find('SELECT#class_id').val(),
                    "price": numeral($(form).find('INPUT#price').val()).value(),
                    "start_date": $(form).find('INPUT#start_date').val(),
                    "end_date": $(form).find('INPUT#end_date').val(),
                    "duration": $(form).find('INPUT#duration').val(),
                    "payer": $(form).find('INPUT#payer').val(),
                    "prepaid": numeral($(form).find('INPUT#prepaid').val()).value(),
                    "amount": numeral($(form).find('INPUT#amount').val()).value(),
                    "discount": numeral($(form).find('INPUT#discount').val()).value(),
                    "discount_type": $(form).find('INPUT#discount-type')[0].checked === true ? 'c' : 'p',
                    "discount_desc": $(form).find('INPUT#discount_desc').val(),
                    "payment_method": $(form).find('INPUT[name="payment_method"]:checked').val(),
                    "invoice_status": 0,
                    "currency": 'VND',
                };
            }
            else if (invoice_type == 'otherfee') {
                data = {
                    "type": 2,
                    "student_id": $(form).find('SELECT#student_id').val(),
                    "class_id": $(form).find('SELECT#class_id').val(),
                    "payer": $(form).find('INPUT#payer').val(),
                    "reason": $(form).find('TEXTAREA#reason').val(),
                    "amount": numeral($(form).find('INPUT#amount').val()).value(),
                    "payment_method": $(form).find('INPUT[name="payment_method"]:checked').val(),
                    "invoice_status": 0,
                    "currency": 'VND',
                }
            }

            if (!eval(invoice_type + '_validate(data)')) {
                return false;
            }
            else {
                if ($(e.target).prop('id') == 'btnSaveInvoice') {
                    save_callback = () => {
                        tab_activate($(tab_headers).find('A[data-tab="invoicelist-tab"]')[0]);
                        invoice_list_table.column('1:visible').order( 'desc' ).draw();
                    };
                }
                else if ($(e.target).prop('id') == 'btnPrintInvoice') {
                    save_callback = (invoce_id) => {
                        print_invoice(invoce_id, () => {
                            invoice_list_table.column('1:visible').order( 'desc' ).draw();
                        });
                        tab_activate($(tab_headers).find('A[data-tab="invoicelist-tab"]')[0]);
                    };
                }

                save_invoice(save_callback, form, data);
            }
        });

    }

    init();

});