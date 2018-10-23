@extends('layouts.master')
@section('title', 'Thời khóa biểu lớp')
@section('breadcrumb')
    <li><a href="{{ asset('class') }}">Danh sách lớp</a></li>
    <li class="active">Thời khóa biểu</li>
@endsection
@section('content')
    <div class="card-body table-reponsive">
        <table class="table table-bordered table-striped" id="timeTableClass">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Ngày</th>
                    <th>Thời gian từ</th>
                    <th>Đến</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>STT</th>
                    <th>Ngày</th>
                    <th>Thời gian từ</th>
                    <th>Đến</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="modal fade" id="edit-timetable">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Edit thời khóa biểu</h4>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" role="form">
                        @csrf
                        <input type="hidden" id="getClass_id">
                        <div class="form-group">
                            <label for="">Chọn ngày</label>
                            <input class="form-control" placeholder="Chọn ngày" id="change-date" readonly type="text">
                        </div>
                        <div class="form-group">
                            <label for="">Chọn giờ</label>
                            <input placeholder="Chọn giờ" id="change-time" readonly type="text" class="form-control">
                        </div>
                        <button data-id="" type="submit" id="update-timetable1" class="btn btn-primary">Update</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<script>
    var asset = "{{ asset('') }}";
</script>
@endsection
