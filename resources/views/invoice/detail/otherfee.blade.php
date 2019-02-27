<div class="row" style="padding-top:20px;"></div>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-info" id="box_parent_list">
            <div class="box-body">
                <form id="frmOtherFee">
                    <input type="hidden" id="iid" value="">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Người đóng tiền: <i class="text-danger">*</i></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="payer" placeholder="Họ tên người đóng tiền (ex: Nguyễn Thị Lan)" style="height:34px;width:100%">
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="student" class="col-sm-2 col-form-label">Học viên <i class="text-danger">*</i></label>
                        <div class="col-sm-3">
                            <select class="form-control select2" name="student_id" id="student_id" style="height:34px;width:100%">
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="class" class="col-sm-2 col-form-label">Lớp <i class="text-danger">*</i></label>
                        <div class="col-sm-3">
                            <select class="form-control select2" name="class_id" id="class_id" style="height:34px;width:100%">
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="reason" class="col-sm-2 col-form-label"> Lý do nộp <i class="text-danger">*</i></label>
                        <div class="col-sm-4">
                            <textarea class="form-control" id="reason" placeholder="Lý do nộp phí"></textarea>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="form-group row" id="group_amount">
                        <label class="col-sm-2 col-form-label">Tổng số tiền: <i class="text-danger">*</i></label>
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">VND</span>
                                <input type="text" class="form-control" id="amount" placeholder="Tổng số tiền" style="height:34px;width:100%">
                            </div>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group row" id="group_note">
                        <label class="col-sm-2 col-form-label">Ghi chú: </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="note" placeholder="Ghi chú" style="height:34px;width:100%">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Người thu: </label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="staff" placeholder="" value="{{ Auth::user()->name }}" readonly style="height:34px;width:100%">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>