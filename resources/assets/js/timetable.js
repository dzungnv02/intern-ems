$(function(){
    var url_string = window.location.href;
    var url = new URL(url_string);
    var id = url.searchParams.get("classid");
	function getTimeEnd(data, type, row) {
	 	var d = moment(data.time,'HH:mm:ss').add(data.duration,'hour').format('HH:mm:ss');
	 	return d;
	}
	function getDayAndDate(data, type, dataToSet) {
	    return data.date + "<br>" + data.week_days;
	}
	var tableTimetable = $('#timeTableClass').DataTable({
    	"columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]],
        ajax: {
	        url: 'api/get-list-timetable',
	        data: {id:id},
	        dataSrc: 'data',
	    },
	    columns: [
            { data: null},
            { data: getDayAndDate,
            	render : function(data, type, row) {
            		var day;
			        switch (row.week_days) {
					    case 0:
					        day = "Chủ nhật";
					        break;
					    case 1:
					        day = "Thứ hai";
					        break;
					    case 2:
					        day = "Thứ ba";
					        break;
					    case 3:
					        day = "Thứ tư";
					        break;
					    case 4:
					        day = "Thứ năm";
					        break;
					    case 5:
					        day = "Thứ sáu";
					        break;
					    case  6:
					        day = "Thứ bảy";
					}
					return row.date + "<br>" + day;
			    },
        	},
            { data: 'time', name: 'time'},
            { data: getTimeEnd},
            {
                'data': null,
                'render': function (data, type, row) {
                    return '<button timetableID=\"'+row.id+'\" title=\"Sửa thời khóa biểu\"'+
                    'class=\"btn btn-warning editTimetable\"><i class=\"fa fa-pencil\" '+
                    'aria-hidden=\"true\"></i>'+
                    '</button> <button classID=\"'+row.class_id+'\" timetableID=\"'+row.id+'\" '+
                    'class="rollCallPage btn btn-success" '+
                    'title="Điểm danh"><i class="fa fa-sticky-note" '+
                    'aria-hidden="true"></i></button>'
                }
            },
        ]
    });
    
    tableTimetable.on( 'order.dt search.dt', function () {
        tableTimetable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    jQuery.datetimepicker.setLocale('vi');
    $('#change-date').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        minDate: '-1970-01-1',
    });
    $('#change-time').datetimepicker({
        format: 'H:i',
        datepicker: false,
        allowTimes: ['07:30','08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00',
        '12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30',
        '18:00','18:30','19:00','19:30','20:00','20:30','21:00'],
    });

    $(document).on('click','.editTimetable', function(){
    	var id = $(this).attr('timetableID');
    	$.ajax({
            type: 'get',
            url: "api/edit-timetable",
            data: {id:id},
            success: function(response){
                $('#edit-timetable').modal('show');
                $('#change-date').val(response['data'][0].date);
                $('#change-time').val(response['data'][0].time);
                $('#getClass_id').val(response['data'][0].class_id);
                $('#update-timetable1').attr('data-id', response['data'][0].id);
            }
        })
    });

    $('#update-timetable1').click(function(e){
    	e.preventDefault();
    	var id = $(this).attr('data-id');
    	var time = $('#change-time').val();
    	var date = $('#change-date').val();
    	var class_id = $('#getClass_id').val();
    	$.ajax({
            type: 'post',
            url: "api/update-timetable",
            data: {id:id,date:date,time:time,class_id:class_id},
            success: function(response){
                if (response.code == 0) {
                	toastr.error(response.message);
                }else{
                	tableTimetable.ajax.reload();
                	$('#edit-timetable').modal('hide');
                	toastr.success('Cập nhật thành công');
                }
            }
        })
    });

    $(document).on('click','.rollCallPage', function(){
    	var idOfTimetable = $(this).attr('timetableID');
		localStorage.setItem("idOfTimetable",idOfTimetable);
		window.location.href = asset + "rollcall";
    });

})