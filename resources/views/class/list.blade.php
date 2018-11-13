@extends('layouts.master')
@section('title', 'Danh sách lớp')
@section('breadcrumb')
<li class="active">Danh sách lớp</li>
@endsection
@section('content')
  <div class="content-class row">
      <div class="col-xs-12">
            <div class="box-header">
            <button type="button" id="button-create-class" class="btn btn-success">
                Thêm lớp
            </button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

            <div  class="table-responsive " style="margin-top: 10px"> 
              <table id="list_class" class="table table-bordered table-striped table-hover dataTable">
                <thead>
                  <tr>
                     <th data-field="id">STT</th>
                     <th data-field="name">Tên Lớp</th>
                     <th data-field="teacher_name">Giáo viên</th>
                     <th data-field="max_seat">Số học sinh tối đa</th>
                     <th data-field="start_date">Ngày khai giảng</th>
                     <th data-field="schedule">Lịch học trong tuần</th>
                     <th data-field="status">Trạng thái</th>
                     <th data-field="action"></th>
                  </tr>
                  </thead>
                  <tbody>     
                </tbody>
              </table>
             </div>
            </div>
          <!-- /.box -->
      </div>
    </div>
<!--Form them moi lop hoc -->
 <div class="modal fade" id="modal-class">
      <div class="modal-dialog" role="document" style="width:870px">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3 class="modal-title">Thêm mới lớp học</h3>
          </div>  
          <div class="modal-body">
          <form id="form-class">
              <input type="hidden" name="id" id="id"> 
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="name">Tên lớp <span class="text-red">*</span> </label>
                    <input type="text" class="form-control" name="class_name" id="class_name"  placeholder="Nhập tên lớp">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="class_code">Mã lớp</label>
                    <input type="text" class="form-control" name="class_code" id="class_code" placeholder="Nhập mã lớp">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="teacher_id">Tên giảng viên</label>
                      <select class="form-control select2 js-states" style="width: 100%;" id="teacher_id" name="teacher_id">
                      </select> 
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="course_name">Chương trình học</label>
                      <input type="text"  class="form-control" name="course_name" id="course_name" placeholder="Chương trình">
                    </div>
                </div>
              </div>
              <div class="row">
                  <div class="col-sm-6">
                      <div class="form-group">
                        <label for="teacher_id">Ngày khai giảng <span class="text-red">*</span> </label>
                        <input type="date" class="form-control" name="start_date" id="start_date" placeholder="Ngày khai giảng"> 
                      </div>
                  </div>
                  <div class="col-sm-3">
                      <div class="form-group">
                        <label for="teacher_id">Số học sinh tối đa </label>
                        <input type="text"  class="form-control" name="max_seat" id="max_seat" placeholder="Số học sinh tối đa"> 
                      </div>
                  </div>
                  <div class="col-sm-3">
                      <div class="form-group">
                        <input type="hidden" name="status" id="status" value="1"> 
                        <label for="status">Trạng thái</label>
                        <div class="btn-group" style="display:inline-block;width: 100%">
                            <button type="button" class="btn btn-warning status_selected">Chưa khai giảng</button>
                            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                              <span class="caret"></span>
                              <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu status_select" role="menu">
                              <li><a class="class_status_1" data-value="1">Chưa khai giảng</a></li>
                              <li><a class="class_status_2" data-value="2">Đang học</a></li>
                              <li><a class="class_status_3" data-value="3">Kết thúc</a></li>
                            </ul>
                          </div>
                    </div>
                  </div>
              </div>
              <hr>
              <div class="row" id="schedule_row">
                  <div class="col-sm-12">
                      <label>Lịch học</label>
                      <table class="table no-border">
                          <tr>
                            <td class="text-center">
                              <input class="form-check-input" type="checkbox" name="schedule_mon" id="schedule_mon" value="mon">&nbsp;
                              <label class="form-check-label" style="font-weight:normal" for="schedule_mon" id="schedule" >Thứ 2</label>
                            </td>
                            <td class="text-center">
                              <input class="form-check-input" type="checkbox" name="schedule" id="schedule_tue" value="tue">&nbsp;
                              <label class="form-check-label" style="font-weight:normal" for="schedule_tue" id="schedule" >Thứ 3</label>
                            </td>
                            <td class="text-center">
                              <input class="form-check-input" type="checkbox" name="schedule" id="schedule_wed" value="wed">&nbsp;
                              <label class="form-check-label" style="font-weight:normal" for="schedule_wed" id="schedule" >Thứ 4</label>
                            </td>
                            <td class="text-center">
                              <input class="form-check-input" type="checkbox" name="schedule" id="schedule_thu" value="thu">&nbsp;
                              <label class="form-check-label" style="font-weight:normal" for="schedule_thu" id="schedule" >Thứ 5</label>
                            </td>
                            <td class="text-center">
                              <input class="form-check-input" type="checkbox" name="schedule" id="schedule_fri" value="fri">&nbsp;
                              <label class="form-check-label" style="font-weight:normal" for="schedule_fri" id="schedule" >Thứ 6</label>
                            </td>
                            <td class="text-center">
                              <input class="form-check-input" type="checkbox" name="schedule" id="schedule_sat" value="sat">&nbsp;
                              <label class="form-check-label" style="font-weight:normal" for="schedule_sat" id="schedule" >Thứ 7</label>
                            </td>
                            <td class="text-center text-danger">
                                <input class="form-check-input" type="checkbox" name="schedule" id="schedule_sun" value="sun">&nbsp;
                                <label class="form-check-label" style="font-weight:normal" for="schedule_sun" id="schedule" >Chủ nhật</label>
                            </td>
                          </tr>
                          <tr>
                            <td class="text-center">
                              <div class="form-group"><input type="time" id="time_start_mon" name="time_start_mon"></div>
                              <div class="form-group"><input type="time" id="time_end_mon" name="time_end_mon"></div>
                            </td>
                            <td class="text-center">
                                <div class="form-group"><input type="time" id="time_start_tue" name="time_start_tue"></div>
                                <div class="form-group"><input type="time" id="time_end_tue" name="time_end_tue"></div>
                            </td>
                            <td class="text-center">
                                <div class="form-group"><input type="time" id="time_start_wed" name="time_start_wed"></div>
                                <div class="form-group"><input type="time" id="time_end_wed" name="time_end_wed"></div>
                            </td>
                            <td class="text-center">
                                <div class="form-group"><input type="time" id="time_start_thu" name="time_start_thu"></div>
                                <div class="form-group"><input type="time" id="time_end_thu" name="time_end_thu"></div>
                            </td>
                            <td class="text-center">
                                <div class="form-group"><input type="time" id="time_start_fri" name="time_start_fri"></div>
                                <div class="form-group"><input type="time" id="time_end_fri" name="time_end_fri"></div>
                            </td>
                            <td class="text-center">
                                <div class="form-group"><input type="time" id="time_start_sat" name="time_start_sat"></div>
                                <div class="form-group"><input type="time" id="time_end_sat" name="time_end_sat"></div>
                            </td>
                            <td class="text-center">
                                <div class="form-group"><input type="time" id="time_start_sun" name="time_start_sun"></div>
                                <div class="form-group"><input type="time" id="time_end_sun" name="time_end_sun"></div>
                            </td>
                          </tr>
                      </table>
                  </div>
              </div>
          </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            <button type="button" class="btn btn-primary" id="create-class" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Đang lưu...">Lưu</button>
          </div>
        </div>
      </div>
</div>
<!--Het form them moi lop hoc -->
<!--Form Danh sach hoc sinh cua lop-->
<div class="modal fade" id="modal-list-student-class">
    <div class="modal-dialog" style="width:800px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-modal-list-student-class" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Danh sách học sinh của lớp</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="class_id" name="class_id">
                <div class="row">
                    <form>
                    <div class="col-sm-9" style="padding-right: 5px;text-align:left">
                        <select style="width:100%" id="student_not_assigned" name="student_not_assigned" class="js-states form-control" multiple="multiple">
                        </select>
                    </div>
                    <div class="col-sm-3" style="padding-left: 5px;text-align:right">
                        <button type="button" class="btn btn-block btn-sm btn-success" id="btnAssignClass" style="height:32px">Thêm học sinh vào lớp</button>
                    </div>
                    </form>
                </div>
                <div class="row" style="height:30px"></div>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-striped" id="table-student-of-class">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên học sinh</th>
                                    <th>Ngày sinh</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>   
                                <tr><td style="text-align:center" colspan="4">Không có học sinh trong lớp</td></tr>  
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-8" style="text-align:left;padding-right:0">
                        <button type="button" class="btn btn-info"><i class="fa fa-print">&nbsp;&nbsp;</i>In danh sách lớp</button>
                        {{--  <button type="button" class="btn btn-warning"><i class="fa fa-check-square-o">&nbsp;&nbsp;</i>Điểm danh</button>  --}}
                    </div>
                    <div class="col-sm-4" style="text-align:right;padding-left:0">
                        <button type="button" class="close-modal-list-student-class btn btn-default" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Het Form Danh sach hoc sinh cua lop -->

{{--  Time table --}}
<div class="modal fade" id="modal-time-table">
    <div class="modal-dialog" style="width:800px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-modal-time-table" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Thời khoá biểu của lớp</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                        <input type="hidden" id="class_id" name="class_id">
                        <input type="hidden" id="start_date" name="start_date">
                        <input type="hidden" id="end_date" name="end_date">
                        <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-12"><label>Khoảng thời gian tính thời khoá biểu</label></div>
                                </div>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input type="text" class="form-control pull-right" id="reservation" style="height:32px;width:100%">
                                </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="row">
                                <div class="col-sm-12"><label>&nbsp;</label></div>
                            </div>
                            <div class="input-group">
                                <button type="button" class="btn btn-sm btn-info" id="btnCalc" style="height:32px;width:103px" data-loading-text="<i class='fa fa-spinner fa-spin'></i>Đang tính..." ><i class="fa fa-calculator">&nbsp;&nbsp;</i>Tính</button>
                            </div>
                        </div>
                </div>
                <div class="row" style="height:30px"></div>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-striped" id="table-timetable">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Ngày</th>
                                    <th>Giờ</th>
                                    <th>Giáo viên</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>   
                                <tr><td style="text-align:center" colspan="5">Chưa có thời khoá biểu</td></tr>  
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-sm-6" style="text-align:left;padding-right:0">
                        <button type="button" class="btn btn-info"><i class="fa fa-print">&nbsp;&nbsp;</i>In thời khoá biểu</button>
                    </div>
                    <div class="col-sm-6" style="text-align:right;padding-left:0">
                            <button type="button" class="btn btn-success" id="btnSave" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Đang lưu..."><i class="fa fa-save">&nbsp;&nbsp;</i>Lưu</button>
                            <button type="button" class="close-modal-time_table btn btn-default" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{--  END -Time table --}}

{{--  Confirm dialog  --}}
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="confirm-delete">
    <div class="modal-dialog modal-sm modal-dialog-centered" style="width:400px">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h5 class="modal-title" id="myModalLabel">Bạn có muốn xoá lớp %s không?</h5>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" id="modal-btn-yes">Có</button>
            <button type="button" class="btn btn-primary" id="modal-btn-no">Không</button>
        </div>
        </div>
    </div>
</div>
{{--  END - Confirm dialog  --}}

@endsection
@section('footer')
<script type="text/javascript">
  var asset = "{{ asset('') }}";
</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection