@extends('layouts.master')
@section('title', 'Danh sách lớp')
@section('breadcrumb')
<li class="active">Danh sách lớp</li>
@endsection
@section('content')
  <div class=" content-class row">
      <div class="col-xs-12">
            <div class="box-header">
            <button type="button" id="button-create-class" class="btn btn-success">
                Thêm lớp
            </button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

            <div  class="table-responsive " style="margin-top: 10px"> 
              <table id="list_class" class="table table-bordered table-striped">
                <thead>
                  <tr>
                     <th data-field="id">STT</th>
                     <th data-field="name">Tên Lớp </th>
                     <th data-field="class_code">Mã lớp</th>
                     <th data-field="teacher_name">Tên giảng viên</th>
                     <th data-field="class_size">Sỉ số</th>
                     <th data-field="start_date">Ngày bắt đầu</th>
                     <th data-field="schedule">Lịch học</th>
                      <th data-field="time_start">Thời gian học</th>
                      <th data-field="status">Trạng thái</th>
                      <th data-field="action">Action</th>
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
 <div class="modal fade" id="modal-create-class">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Thêm mới lớp học</h4>
          </div>  
          <div class="modal-body">
          <form id="form-create-class">
              <div class="form-group">
                <label for="exampleInputText">Mã lớp</label>
                <input type="text" class="form-control" name="class_code" id="class_code"  placeholder="Nhập mã lớp">
              </div>
              <div class="form-group">
                <label for="exampleInputText">Tên lớp</label>
                <input type="text" class="form-control" name="name" id="name"   placeholder="Nhập tên lớp">
              </div>
              <div class="form-group">
                <label for="exampleInputText">Tên giảng viên</label>
                <select class="form-control form-control-sm" id="name_teacher">
                </select>
              </div>
              <div class="form-group">
              <label for="exampleInputText">Ngày bắt đầu</label>
                <input type="text" readonly class="form-control" name="start_date" id="start_date"   placeholder="Nhập ngày bắt đầu">
              </div>
                <div class="form-group">
                <label for="exampleInputText">Lịch học</label>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" name="schedule" id="schedule_1" value="1">
                      <label class="form-check-label" for="schedule_1" id="schedule" >Thứ 2</label>&nbsp;&nbsp;
                      <input class="form-check-input" value="2" type="checkbox" id="schedule_2" >
                      <label class="form-check-label" for="schedule_2">Thứ 3</label>&nbsp;&nbsp;
                      <input class="form-check-input" value="3" type="checkbox" name="schedule" id="schedule_3"  >
                      <label class="form-check-label" for="schedule_3">Thứ 4</label>&nbsp;&nbsp;
                      <input class="form-check-input" value="4" type="checkbox" name="schedule" id="schedule_4"  >
                      <label class="form-check-label" for="schedule_4">Thứ 5</label>&nbsp;&nbsp;
                      <input class="form-check-input" value="5" type="checkbox" name="schedule" id="schedule_5"  >
                      <label class="form-check-label" for="schedule_5">Thứ 6</label>&nbsp;&nbsp;
                      <input class="form-check-input" value="6" type="checkbox" name="schedule" id="schedule_6"  >
                      <label class="form-check-label" for="schedule_6">Thứ 7</label>&nbsp;&nbsp;
                      <input class="form-check-input" value="0" type="checkbox" name="schedule" id="schedule_7" >
                      <label class="form-check-label" for="schedule_7">Chủ nhật</label>
                    </div> 

                </div>
              <div class="form-group">
                  <label for="exampleInputText">Thời gian bắt đầu</label>
                  <input type="time" class="form-control" name="time_start" id="time_start">
                </div>
              <div class="form-group">
                <label for="exampleInputText">Thời lượng</label>
                <input type="text" class="form-control" name="duration" id="duration" placeholder="Nhập thời lượng">
              </div>
              <div class="form-group">
                <label for="exampleInputText">Sĩ số</label>
                <input type="text" class="form-control" id="class_size" name="class_size"  placeholder="Nhập sĩ số">
              </div>
              <div class="form-group">
                <label for="exampleInputText">Tên khóa học</label>
                <select class="form-control form-control-sm" id="name_course">
                    
                </select>
              </div>
      </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="create-class">Save</button>
          </div>
        </div>
      </div>
  </div>
<!--Het form them moi khoa hoc -->
<!--Form Sua lop hoc -->
 <div class="modal fade" id="modal-edit-class">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Sửa lớp học</h4>
          </div>  
          <div class="modal-body">
          <form id="form-edit-class">
              <div class="form-group">
                <label for="exampleInputText">Mã lớp</label>
                <input type="text" class="form-control" name="class_code_edit" id="class_code_edit"  placeholder="Nhập mã lớp">
              </div>
              <div class="form-group">
                <label for="exampleInputText">Tên lớp</label>
                <input type="text" class="form-control" name="name_edit" id="name_edit"   placeholder="Nhập tên lớp">
              </div>
              <div class="form-group">
              <label for="exampleInputText">Ngày bắt đầu</label>
                <input type="date" class="form-control" name="start_date_edit" id="start_date_edit"   placeholder="Nhập ngày bắt đầu" disabled>
              </div>
                
              <div class="form-group">
                  <label for="exampleInputText">Thời gian bắt đầu</label>
                  <input type="time" class="form-control" name="time_start_edit" id="time_start_edit" disabled>
                </div>
              <div class="form-group">
                <label for="exampleInputText">Thời lượng</label>
                <input type="text" class="form-control" name="duration_edit" id="duration_edit" placeholder="Nhập thời lượng" disabled>
              </div>
              <div class="form-group">
                <label for="exampleInputText">Sĩ số</label>
                <input type="text" class="form-control" id="class_size_edit" name="class_size_edit"  placeholder="Nhập sĩ số">
              </div>
      </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" data-id="" class="button-edit-class btn btn-primary">Save</button>
          </div>
        </div>
      </div>
  </div>
<!--Het form sua lop hoc -->
<!--Form Them moi hoc sinh vao lop-->
    <div class="modal fade" id="modal-add-student-class">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class=" close close-modal-add-student-class" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Danh sách học sinh</h4>
                </div>
                <div class="modal-body">
                   <input type="hidden" name="" value="" id="get_class_id12">
                    <table class="table table-bordered table-striped" id="table-student-class">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên học sinh</th>
                                <th>Địa chỉ</th>
                                <th>Số điện thoại</th>
                                <th>Ngày sinh</th>
                                <th>Giới tính</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>     
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="close-modal-add-student-class btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<!--Het Them moi hoc sinh vao lop -->
<!--Form Danh sach hoc sinh cua lop-->
    <div class="modal fade" id="modal-list-student-class">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close-modal-list-student-class" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Danh sách học sinh của lớp</h4>
                </div>
                <div class="modal-body">
                   <input type="hidden" name="" value="" id="get_class_id2">
                    <table class="table table-bordered table-striped" id="table-student-of-class1">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên học sinh</th>
                                <th>Địa chỉ</th>
                                <th>Số điện thoại</th>
                                <th>Ngày sinh</th>
                                <th>Giới tính</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>     
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class=" close-modal-list-student-class btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<!--Het Form Danh sach hoc sinh cua lop -->
@endsection
@section('footer')
<script type="text/javascript">
  var asset = "{{ asset('') }}"
</script>
@endsection