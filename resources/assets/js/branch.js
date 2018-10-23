$(function () {
    var tableClass = $('#branch-list').DataTable({
        "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        }],
        ajax: {
            url: 'api/branch/list',
            dataSrc: function ( json ) {
                var data = [];
                for (var i = 0; i < json.data.length; i++) {
                    data[i] = [
                        json.data[i].id,
                        json.data[i].branch_name,
                        json.data[i].address,
                        json.data[i].phone_1,
                        json.data[i].phone_2,
                        json.data[i].email,
                        json.data[i].leader > 0 ? json.data[i].leader : ''
                    ];
                }
                return data;
            },
        }
    });
});