<div class="row" style="padding-top:20px;"></div>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-info" id="box_parent_list">
            <div class="box-body">
                <form id="frmTutorFee">
                    <div class="form-group row">
                        <label for="student" class="col-sm-2 col-form-label">Học viên *</label>
                        <div class="col-sm-8">
                            <select class="form-control select2" name="student_id" id="student_id" style="height:34px;width:100%">
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="class" class="col-sm-2 col-form-label">Lớp *</label>
                        <div class="col-sm-8">
                            <select class="form-control select2" name="class_id" id="class_id" style="height:34px;width:100%">
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="class" class="col-sm-2 col-form-label">Học phí mỗi buổi học *</label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">VND</span>
                                <input type="text" class="form-control" id="price" placeholder="" style="height:34px;width:100%">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Thời gian </label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                <input type="text" class="form-control pull-right" id="reservation" style="height:34px;width:100%">
                                <input type="hidden" id="start_date">
                                <input type="hidden" id="end_date">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row" id="group_duration">
                        <label class="col-sm-2 col-form-label">Số buổi học: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="duration" placeholder="Số buổi học" style="height:34px;width:100%">
                        </div>
                    </div>
                    
                    <div class="form-group row" id="discount">
                        <label class="col-sm-2 col-form-label">Chiết khấu: </label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">%</span>
                                <input type="text" class="form-control" id="discount" placeholder="phần trăm chiết khấu" style="height:34px;width:100%">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row" id="discount">
                        <label class="col-sm-2 col-form-label">Lý do: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="discount-desc" placeholder="lý do chiết khấu" style="height:34px;width:100%">
                        </div>
                    </div>

                    <div class="form-group row" id="group_amount">
                        <label class="col-sm-2 col-form-label">Đã tạm ứng: </label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">VND</span>
                                <input type="text" class="form-control" id="prepaid" placeholder="Số tiền tạm ứng" style="height:34px;width:100%">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row" id="group_amount">
                        <label class="col-sm-2 col-form-label">Tổng số tiền: </label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">VND</span>
                                <input type="text" class="form-control" id="amount" placeholder="Tổng số tiền" style="height:34px;width:100%">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Người đóng tiền: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="payer" placeholder="Họ tên và vai trò người đóng tiền (ex: Mẹ Nguyễn Thị Lan)" style="height:34px;width:100%">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Người thu: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="staff" placeholder="" readonly style="height:34px;width:100%">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>