 @extends('layouts.master')
 @section('header')
 @endsection
 @section('title')
 Danh sách chi nhánh
 @endsection
 @section('content')
  <div class=" content-class row">
      <div class="col-xs-12">
            <div class="box-header">
            <button type="button" id="button-create-class" class="btn btn-success">
                Thêm chi nhánh
            </button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

            <div  class="table-responsive " style="margin-top: 10px"> 
              <table id="branch-list" class="table table-bordered table-striped">
                <thead>
                  <tr>
                     <th data-field="id">STT</th>
                     <th data-field="branch_name">Tên chi nhánh</th>
                     <th data-field="address">Địa chỉ</th>
                     <th data-field="phone_1">Số ĐT (1)</th>
                     <th data-field="phone_2">Số ĐT (2)</th>
                     <th data-field="email">E-mail</th>
                     <th data-field="leader">Người liên lạc</th>
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
 @endsection
 @section('footer')
 @endsection