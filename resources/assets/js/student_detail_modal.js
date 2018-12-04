$(function () {
    var modal_branch = $('DIV#branch-select-modal');
    var modal_teacher = $('DIV#teacher-select-modal');
    var modal_classes = $('DIV#classes-select-modal');
    var modal_staff = $('DIV#staff-select-modal');

    var table_branch = null;
    var table_teacher = null;
    var table_classes = null;
    var table_staff = null;

    var branch_list_render = () => {
        
        if ($.fn.dataTable.isDataTable('DIV#branch-select-modal TABLE#branch-list-student')) {
            table_student = $(modal_branch).find('TABLE#branch-list-student').DataTable();
            table_branch.ajax.reload();
        }else {
            table_branch = $(modal_branch).find('TABLE#branch-list-student').DataTable({
                language: datatable_language,
                "ordering": false,
                ajax: {
                    url: '/api/branch/list'
                },
                columns: [{
                        data: null
                    },
                    {
                        data: 'branch_name'
                    },
                    {
                        data: 'address'
                    },
                    {
                        data: null
                    }
                ],
                    "columnDefs": [
                    {
                        "targets": [0],
                        "data": null,
                        "defaultContent": ''
                    }
                    ,{
                        targets: 3,
                        "data": null,
                        "visible": true,
                        "defaultContent": '<span class="badge bg-dark" style="margin-left:30px"><a title="Chọn làm liên hệ chính" class="text-white branch-selection" style="color:white;cursor: pointer">Chọn</a></span>'
                    }
                ],
            });

            table_branch.on('order.dt search.dt', function () {
                table_branch.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

            table_branch.on('click', 'a.branch-selection', function () {
                var data = table_branch.row($(this).parents('tr')).data();
                var form = $('FORM#frmStudent');
                var target_prefix = $(modal_branch).find('INPUT#target-form-control[type="hidden"]').val();
                $(form).find('INPUT#' + target_prefix + '_id').val(data.id);
                $(form).find('INPUT#branch-' + target_prefix + '_name').val(data.branch_name);
                $(modal_branch).modal('hide');
            });
        }
    }

    var events_binding = () => {
        $('A#btn_register_branch').on('click', (e) => {
            $(modal_branch).find('INPUT#target-form-control[type="hidden"]').val('register_branch');
            modal_branch.modal('show');
        });

        $('A#btn_dependent_branch').on('click', (e) => {
            $(modal_branch).find('INPUT#target-form-control[type="hidden"]').val('dependent_branch');
            modal_branch.modal('show');
        });

        $('A#btn_staff').on('click', (e) => {
            modal_staff.modal('show');
        });

        $('A#btn_trial_class').on('click', (e) => {
            modal_classes.modal('show');
        });

        $('A#btn_teacher').on('click', (e) => {
            modal_teacher.modal('show');
        });

        modal_branch.on('show.bs.modal', (e) => {
            branch_list_render();
        });
    }

    events_binding();
});