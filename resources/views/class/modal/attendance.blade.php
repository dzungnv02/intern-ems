<div class="modal fade" id="attendance-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:900px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="attendance-modal-title"></h3>
            </div>
            <div class="modal-body">
                <input type="hidden" id="current-class-id" value="">
                <input type="hidden" id="current-class-name" value="">

                {{--  <div class="row"> 
                    <div class="col-sm-12 form-group bg-dark">
                        <label for="name" class="col-sm-2 control-label">Nghỉ cả lớp</label>
                        <div class="col-sm-10">
                            <input type="checkbox" id="class-off">
                        </div>
                    </div>
                </div>  --}}
                <div class="row"> 
                    <div class="col-sm-12">
                        <table id="attendance-table" class="table table-striped">
                            <thead>
                                <tr class="border-top">
                                    <th class="bg-dark border-top" style="width:5%;vertical-align: middle;text-align:center" rowspan="2">#</th>
                                    <th class="bg-dark border-top" style="width:20%;vertical-align: middle;text-align:left" rowspan="2">Học sinh</th>
                                    <th class="bg-dark border-top" style="width:12%;vertical-align: middle;text-align:center" rowspan="2">Số buổi<br>đi học</th>
                                    <th class="bg-dark border-top" style="width:12%;vertical-align: middle;text-align:center" rowspan="2">Số buổi<br>nghỉ</th>
                                    <th class="bg-dark border-top" style="width:12%;vertical-align: middle;text-align:center" rowspan="2">Số buổi<br>muộn</th>
                                    <th class="bg-warning border-top" style="vertical-align: middle;text-align:center" colspan="3">Hôm nay</th>
                                </tr>
                                <tr>
                                    <th class="bg-success" style="width:13%;vertical-align: middle;text-align:center">Đi học</th>
                                    <th class="bg-danger" style="width:13%;vertical-align: middle;text-align:center">Vắng</th>
                                    <th class="bg-info" style="width:13%;vertical-align: middle;text-align:center">Đi muộn</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-success"><i class="fa fa-save">&nbsp;</i>Lưu</button>
            </div>
        </div>
    </div>
</div>