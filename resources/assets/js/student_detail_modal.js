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
                "lengthChange": false,
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
                        "defaultContent": '<span class="badge bg-dark" style="margin-left:30px"><a title="Chọn trung tâm" class="text-white branch-selection" style="color:white;cursor: pointer">Chọn</a></span>'
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

    var classes_list_render = () => {
        if ($.fn.dataTable.isDataTable('DIV#classes-select-modal TABLE#classes-list-student')) {
            table_classes = $(modal_classes).find('TABLE#classes-list-student').DataTable();
        }
        else {
            table_classes = $(modal_classes).find('TABLE#classes-list-student').DataTable({
                language: datatable_language,
                "ordering": false,
                "lengthChange": false,
                ajax: {
                    url: '/api/get-list-class'
                },
                columns: [{
                        data: null
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'branch_name'
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
                        "defaultContent": '<span class="badge bg-dark" style="margin-left:30px"><a title="Chọn lớp" class="text-white classes-selection" style="color:white;cursor: pointer">Chọn</a></span>'
                    }
                ],
            });

            table_classes.on('order.dt search.dt', function () {
                table_classes.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

            table_classes.on('click', 'a.classes-selection', function () {
                var data = table_classes.row($(this).parents('tr')).data();
                var form = $('FORM#frmStudent');
                $(form).find('INPUT#assessment_trial_class_id').val(data.id);
                $(form).find('INPUT#trial_class').val(data.name);
                $(modal_classes).modal('hide');
            });
        }
    }

    var teacher_list_render = () => {
        if ($.fn.dataTable.isDataTable('DIV#teacher-select-modal TABLE#teacher-list-student')) {
            table_teacher = $(modal_teacher).find('TABLE#teacher-list-student').DataTable();
        }
        else {
            table_teacher = $(modal_teacher).find('TABLE#teacher-list-student').DataTable({
                language: datatable_language,
                "ordering": false,
                "lengthChange": false,
                ajax: {
                    url: '/api/list-teachers'
                },
                columns: [{
                        data: null
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'email'
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
                        "defaultContent": '<span class="badge bg-dark" style="margin-left:30px"><a title="Chọn lớp" class="text-white teacher-selection" style="color:white;cursor: pointer">Chọn</a></span>'
                    }
                ],
            });

            table_teacher.on('order.dt search.dt', function () {
                table_teacher.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

            table_teacher.on('click', 'a.teacher-selection', function () {
                var data = table_teacher.row($(this).parents('tr')).data();
                var form = $('FORM#frmStudent');
                $(form).find('INPUT#assessment_teacher_id').val(data.id);
                $(form).find('INPUT#assessment_teacher').val(data.name);
                $(modal_teacher).modal('hide');
            });
        }
    }

    var staff_list_render = () => {
        if ($.fn.dataTable.isDataTable('DIV#staff-select-modal TABLE#staff-list-student')) {
            table_staff = $(modal_staff).find('TABLE#staff-list-student').DataTable();
        }
        else {
            table_staff = $(modal_staff).find('TABLE#staff-list-student').DataTable({
                language: datatable_language,
                "ordering": false,
                "lengthChange": false,
                ajax: {
                    url: '/api/get-list-staff'
                },
                columns: [{
                        data: null
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'branch_name'
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
                        targets: 4,
                        "data": null,
                        "visible": true,
                        "defaultContent": '<span class="badge bg-dark" style="margin-left:30px"><a title="Chọn lớp" class="text-white staff-selection" style="color:white;cursor: pointer">Chọn</a></span>'
                    }
                ],
            });

            table_staff.on('order.dt search.dt', function () {
                table_staff.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

            table_staff.on('click', 'a.staff-selection', function () {
                var data = table_staff.row($(this).parents('tr')).data();
                var form = $('FORM#frmStudent');
                $(form).find('INPUT#staff_id').val(data.id);
                console.log(data);
                $(form).find('INPUT#dependent_staff_name').val(data.name);
                $(modal_staff).modal('hide');
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

        modal_classes.on('show.bs.modal', (e) => {
            classes_list_render();
        });

        modal_teacher.on('show.bs.modal', (e) => {
            teacher_list_render();
        });

        modal_staff.on('show.bs.modal', (e) => {
            staff_list_render();
        });
    }

    events_binding();

});