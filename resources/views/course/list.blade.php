@extends('layouts.master')
@section('page-title')
  Danh sách khóa học
@endsection
@section('title')
  Danh sách khóa học<span></span>
@endsection
@section('breadcrumb')
  <li class="active">Danh sách khóa học</li>
@endsection
@section('content')

  {{-- button thêm mới khóa học --}}
  <button  class="btn btn-info" type="button" id="button-create-course">Thêm mới</button><br><br>

  <div class="card-body table-reponsive">
    {{-- bảng danh sách khóa học --}}
    <table class="table table-bordered table-striped" id="list-course">
      <thead>
        <tr>
          <th>STT</th>
          <th>Mã khóa học</th>
          <th>Tên khóa học</th>
          <th>Trình độ</th>
          <th>Giáo trình</th>
          <th>Thời lượng(h)</th>
          <th>Học phí</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
      <tfoot>
        <tr>
          <th>STT</th>
          <th>Mã khóa học</th>
          <th>Tên khóa học</th>
          <th>Trình độ</th>
          <th>Giáo trình</th>
          <th>Thời lượng</th>
          <th>Học phí</th>
          <th>Action</th>
        </tr>
      </tfoot>
    </table>
  </div></br>
 <!-- Form editCourse-->
    <div class="modal fade" id="modal-edit-course">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Sửa khóa học</h4>
          </div>  
          <div class="modal-body">
              <div class="contain-form">
                  <form action=""  role="form" id="form-edit-course">
                     <input type="hidden" name="" value="" id="get_course_id1">
                    <div class="form-group">
                      <label for="">Mã khóa học</label>
                      <input name="code" type="text" class="form-control" id="code_edit">
                    </div>
                    <div class="form-group">
                      <label for="">Tên khóa học</label>
                      <input name="name" type="text" class="form-control" id="name_edit">
                    </div>
                    <div class="form-group">
                      <label for="">Giáo trình</label>
                      <input name="curriculum" type="text" class="form-control" id="curriculum_edit">
                    </div>
                    <div class="form-group">
                      <label for="">Trình độ</label>
                      <select class="form-control" id="level_edit">
                        <option value="1" id="advance" >Nâng cao</option>
                        <option value="0" id="beginner">Cơ bản</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="">Thời lượng (giờ)</label>
                      <input name="duration" type="text" class="form-control" id="duration_edit" disabled="">
                    </div>
                    <div class="form-group">
                      <label for="">Học phí</label>
                      <input name="fee" type="text" class="form-control" id="fee_edit">
                    </div>
                  </form>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="button-edit-course btn btn-primary" >Update
            </button>
          </div>
        </div>
      </div>
  </div>
<!-- Form CreateCourse-->
    <div class="modal fade" id="modal-create-course">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Thêm khóa học</h4>
          </div>  
          <div class="modal-body">
              <div class="contain-form">
                  <form action=""  role="form" id="form-create-course">
                    <div class="form-group">
                      <label for="">Mã khóa học</label>
                      <input name="code" type="text" class="form-control" id="code">
                    </div>
                    <div class="form-group">
                      <label for="">Tên khóa học</label>
                      <input name="name" type="text" class="form-control" id="name">
                    </div>
                    <div class="form-group">
                      <label for="">Giáo trình</label>
                      <input name="curriculum" type="text" class="form-control" id="curriculum">
                    </div>
                    <div class="form-group">
                      <label for="">Trình độ</label>
                      <select class="form-control" id="level">
                        <option value="1" id="advance" >Nâng cao</option>
                        <option value="0" id="beginner">Cơ bản</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="">Thời lượng (giờ)</label>
                      <input name="duration" type="text" class="form-control" id="duration">
                    </div>
                    <div class="form-group">
                      <label for="">Học phí</label>
                      <input name="fee" type="text" class="form-control" id="fee">
                    </div>
                  </form>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="create-course">Save</button>
          </div>
        </div>
      </div>
  </div>
@endsection