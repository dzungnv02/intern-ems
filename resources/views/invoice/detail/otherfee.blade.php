<div class="row" style="padding-top:20px;"></div>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-info" id="box_parent_list">
            <div class="box-body">
                <form id="frmOtherFee">
                    <div class="form-group row">
                        <label for="student" class="col-sm-2 col-form-label">Học viên *</label>
                        <div class="col-sm-8">
                            <select class="form-control select2" name="student_id" id="student_id" style="width: 100%;">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="class" class="col-sm-2 col-form-label">Lớp *</label>
                        <div class="col-sm-8">
                            <select class="form-control select2" name="class_id" id="class_id" style="width: 100%;">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="reason" class="col-sm-2 col-form-label"> Lý do nộp *</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="reason" placeholder="Lý do nộp phí"></textarea>
                        </div>
                    </div>
                    <div class="form-group row" id="group_amount">
                        <label class="col-sm-2 col-form-label">Tổng số tiền: </label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">VND</span>
                                <input type="text" class="form-control" id="amount" placeholder="Tổng số tiền">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Người đóng tiền: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="payer" placeholder="payer">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Người thu: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="staff" placeholder="staff">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>