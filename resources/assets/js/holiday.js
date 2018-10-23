$(function () {
    var tableHoliday = $('#list-holiday').DataTable({
    	"columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]],
        ajax: {
	        url: 'api/get-list-holiday',
	        dataSrc: 'data',
	    },
	    columns: [
            { data: null},
            { data: 'holiday', name: 'holiday'},
            {
                'data': null,
                'render': function (data, type, row) {
                    return '<button holidayID=\"'+row.id+'\" title=\"xóa ngày nghỉ\" '+
                    'class=\"deleteHoliday btn btn-danger\"><i class=\"fa fa-trash\" '+
                    'aria-hidden=\"true\"></i></button>'
                }
            },
        ]
    });
    
    tableHoliday.on( 'order.dt search.dt', function () {
        tableHoliday.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    $('.add-holiday').click(function(){
    	$('#add-holiday').modal('show');
    });

	var formAddHoliday = $('#form-add-holiday');
    formAddHoliday.validate({
		rules: {
			"holiday": {
				required: true,
				date: true
			},
		},
		messages: {
			"holiday": {
				required: "Bắt buộc nhập ngày",
				date: "Hãy nhập đúng định dạng ngày"
			},
		},
		highlight: function(element, errorClass) {
			$(element).closest(".form-group").addClass("has-error");
		},
		unhighlight: function(element, errorClass) {
			$(element).closest(".form-group").removeClass("has-error");
		},
		errorPlacement: function (error, element) {
			error.appendTo(element.parent().next());
		},
		errorPlacement: function (error, element) {
			if(element.attr("type") == "checkbox") {
				element.closest(".form-group").children(0).prepend(error);
			}
			else
				error.insertAfter(element);
		}
	});
    
    $('#store-holiday').click(function(e){
    	e.preventDefault();
    	var holiday = $('#holiday').val();
    	if(formAddHoliday.valid()){
    		$.ajax({
	            type: 'POST',
	            url: 'api/add-holiday',
	            data: {holiday: holiday},
	            success: function(response){
	            	$('#add-holiday').modal('hide');
	            	$('#add-holiday').on('hidden.bs.modal', function(){
					    $(this).find('form')[0].reset();
					});
	            	tableHoliday.ajax.reload();
	     			toastr.success('Thêm thành công!');
	            }
	        });
    	}
    });

    $(document).on('click', '.deleteHoliday', function(){
    	var id = $(this).attr('holidayID');
        swal({
	        title: "Bạn có chắc muốn xóa?",
	        text: "Bạn sẽ không thể khôi phục lại bản ghi này!",
	        icon: "warning",
	        buttons: true,
	        dangerMode: true,
	    })
	    .then((willDelete) => {
	      	if (willDelete) {
      			$.ajax({
		            type: 'post',
		            url: 'api/delete-holiday',
		            data: {id:id},
		            success: function(response){
		            	tableHoliday.ajax.reload();
		                toastr.success('Xóa thành công!');
		            }
		        })
	      	}
	    });
    });

    jQuery.datetimepicker.setLocale('vi');
    $('#holiday').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        minDate: '-1970-01-1',
    });

});