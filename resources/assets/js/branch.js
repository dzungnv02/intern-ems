$(function () {
    var tableBranch = $('#branch-list').DataTable({
        language: datatable_language,
        ajax: {
            url: 'api/branch/list'
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
                data: 'email',
                render: (data, type, row) => {
                    return data != '' ? '<a href="mailto:'+data+'">'+data+'</a>' : '';
                }
            },
            {
                data: 'phone_1'
            },
            {
                data: null
            }
        ],
        "columnDefs": [{
            "targets": -1,
            "data": null,
            //"defaultContent": '<a class="edit">Sửa</a>&nbsp;&nbsp;<a class="delete">Xoá</a>'
            "defaultContent": ''
        }],
    });

    tableBranch.on('order.dt search.dt', function () {
        tableBranch.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
});