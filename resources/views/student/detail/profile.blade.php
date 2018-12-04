<form id="frmStudent" class="form-horizontal">

    <input type="hidden" name="student_id" id="student_id">
    <input type="hidden" name="parent_id" id="parent_id">
    <input type="hidden" name="register_branch_id" id="register_branch_id">
    <input type="hidden" name="dependent_branch_id" id="dependent_branch_id">
    <input type="hidden" name="staff_id" id="staff_id">
    <input type="hidden" name="assessment_teacher_id" id="assessment_teacher_id">
    <input type="hidden" name="assessment_trial_class_id" id="assessment_trial_class_id">

    <div class="row" style="padding-top:20px;"></div>
    <div class="row">
        <div class="col-sm-4">
            <div class="box box-info" id="box_student" style="height:300px">
                <div class="box-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Họ tên<i style="color:red">*</i></label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="student-name" placeholder="họ tên học sinh">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Tên tiếng Anh</label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" id="student-e_name" placeholder="tên tiếng Anh">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Giới tính</label>
                        <div class="col-sm-7">
                            <select class="form-control" id="student-gender" placeholder="giới tính">
                                <option value="0">Nữ<option>
                                <option value="1">Nam<option>
                            </select>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Năm sinh</label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" id="student-birthyear" placeholder="năm sinh">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Ngày sinh</label>
                        <div class="col-sm-7">
                        <input type="date" class="form-control" id="student-birthday" placeholder="ngày sinh">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="box box-info" id="box_parent" style="height:300px">
                <div class="box-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Tên phụ huynh</label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" id="parent-fullname" placeholder="tên phụ huynh" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Vai trò</label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" id="parent-parent_role" placeholder="Bố, mẹ, ông, bà..." readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Điện thoại</label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" id="parent-phone" placeholder="số điện thoại của phụ huynh" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">E-mail</label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" id="parent-email" placeholder="e-mail của phụ huynh" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Tài khoản MXH</label>
                        <div class="col-sm-7">
                        <input type="text" class="form-control" id="parent-facebook" placeholder="trang facebook, twitter... của phụ huynh" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 text-right">
                            <button type="button" class="btn btn-sm btn-info" id="btn_change_parent"><i class="fa fa-ellipsis-h"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="box box-info" id="box_branch" style="height:300px">
                <div class="box-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Đăng ký tại</label>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <input type="text" class="form-control" id="branch-register_branch_name" placeholder="trung tâm đăng ký" readonly>
                                <div class="input-group-addon" style="padding:0;margin:0">
                                    <a id="btn_register_branch" style="display:block;height:20px;width:30px;margin:8px 0 0px 0;cursor:pointer"><i class="fa fa-ellipsis-h"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Ngày đăng ký</label>
                        <div class="col-sm-7">
                        <input type="date" class="form-control" id="register_date" placeholder="ngày đăng ký">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Trung tâm phụ trách</label>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <input type="text" class="form-control" id="branch-dependent_branch_name" placeholder="trung tâm phụ trách" readonly>
                                <div class="input-group-addon" style="padding:0;margin:0">
                                <a id="btn_dependent_branch" style="display:block;height:20px;width:30px;margin:8px 0 0px 0;cursor:pointer"><i class="fa fa-ellipsis-h"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Nhân viên phụ trách</label>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <input type="text" class="form-control" id="dependent_staff_name" placeholder="nhân viên phụ trách" readonly>
                                <div class="input-group-addon" style="padding:0;margin:0">
                                <a id="btn_staff" style="display:block;height:20px;width:30px;margin:8px 0 0px 0;cursor:pointer"><i class="fa fa-ellipsis-h"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Ghi chú</label>
                        <div class="col-sm-7">
                        <textarea rows="3" class="form-control" id="register_note" placeholder="ghi chú"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="padding-top:20px;"></div>
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-info" id="box_assessment" style="height:220px">
                <div class="box-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Tình trạng Assessment<i style="color:red">*</i></label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="assessment_status" placeholder="tình trạng assessment" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Ngày làm Assessment</label>
                        <div class="col-sm-7">
                            <input type="datetime-local" class="form-control" id="assessment_date" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Giáo viên</label>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <input type="text" class="form-control" id="assessment_teacher" placeholder="giáo viên assessment" readonly>
                                <div class="input-group-addon" style="padding:0;margin:0">
                                <a id="btn_teacher" style="display:block;height:20px;width:30px;margin:8px 0 0px 0;cursor:pointer"><i class="fa fa-ellipsis-h"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Kết qủa</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="assessment_result" placeholder="kết quả assessment">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-info" id="box_trial" style="height:220px">
                <div class="box-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Tình trạng học thử<i style="color:red">*</i></label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="trial_status" placeholder="tình trạng học thử" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Lớp học thử</label>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <input type="text" class="form-control" id="trial_class" placeholder="lớp học thử" readonly>
                                <div class="input-group-addon" style="padding:0;margin:0">
                                <a id="btn_trial_class" style="display:block;height:20px;width:30px;margin:8px 0 0px 0;cursor:pointer"><i class="fa fa-ellipsis-h"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Ngày học thử</label>
                        <div class="col-sm-7">
                            <input type="datetime-local" class="form-control" id="trial_start_date" placeholder="ngày học thử">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <div class="row" style="padding-top:20px;"></div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-info" id="box_history">
                <div class="box-header">
                    <h5>Các hoạt động của học sinh</h5>
                </div>
                <div class="box-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" style="width:5%">#</th>
                                <th scope="col" style="width:20%">Hoạt động</th>
                                <th scope="col" style="width:50%">Mô tả</th>
                                <th scope="col" style="width:20%">Thời gian</th>
                                <th scope="col" style="width:5%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Làm assessment</td>
                                <td>Kiểm tra đầu vào với giáo viên AAA, kết quả: OK</td>
                                <td>2018-11-15 09:00</td>
                                <td><a>Thay đổi</a></td>
                            </tr>
                        </tbody>
                        <tfoot>
                                <tr>
                                    <th scope="row"></th>
                                    <td>
                                        <select class="form-control" id="act_type">
                                            <option>[Chọn hoạt động]</option>
                                        </select>
                                    </td>
                                    <td><input type="text" id="note" class="form-control"></td>
                                    <td><input type="datetime-local" id="start_time" class="form-control"></td>
                                    <td><a role="button" id="btnSaveActivity" class="btn btn-sm btn-success" style="height:34px;width:34px"><i class="fa fa-save"></i></a></td>
                                </tr>
                             </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
@include('student.modal.branch')
@include('student.modal.teacher')
@include('student.modal.classes')
@include('student.modal.staff')