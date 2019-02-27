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
            {{--  <button type="button" id="button-create-class" class="btn btn-success">
                Thêm chi nhánh
            </button>  --}}
            </div>
            <!-- /.box-header -->
            <div class="box-body">

            <div  class="table-responsive " style="margin-top: 10px"> 
              <table id="branch-list" class="table table-bordered table-striped">
                <thead>
                  <tr>
                     <th>STT</th>
                     <th>Tên chi nhánh</th>
                     <th>Địa chỉ</th>
                     <th>E-mail</th>
                     <th>Số ĐT (1)</th>
                     <th></th>
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