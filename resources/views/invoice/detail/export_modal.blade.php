<div class="modal fade" id="invoice-export-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:700px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="attendance-modal-title"></h3>
            </div>
            <div class="modal-body">
                <div class="row"> 
                    <div class="col-sm-12">
                        <form id="frmExportInvoice">
                            <div class="row">
                                <div class="form-group col-sm-2">
                                    <label for="start_date">Ngày bắt đầu </label>
                                </div>
                                <div class="form-group col-sm-4">
                                    <div class="input-group date">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input type="text" class="form-control pull-right" id="start_date" class="form-input" placeholder="Ngày bắt đầu" />
                                    </div>
                                </div>
                                <div class="form-group col-sm-2">
                                    <label for="end_date">Ngày kết thúc </label>
                                </div>
                                <div class="form-group col-sm-4">
                                    <div class="input-group date">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input type="text" class="form-control pull-right" id="end_date" class="form-input" placeholder="Ngày kết thúc" />
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="height:10px"></div>
                            <div class="row">
                                <div class="form-group col-sm-2">
                                    <label>Loại hoá đơn</label>
                                </div>
                                <div class="form-group col-sm-4">
                                    <div class="radio">
                                        <label for="invoice_type-0">
                                            <input type="radio" name="invoice_type" id="invoice_type-0" class="form-input" value="0" checked/>
                                            Tất cả
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label for="invoice_type-1">
                                            <input type="radio" name="invoice_type" id="invoice_type-1" class="form-input" value="1"/>
                                            Hoá đơn học phí
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label for="invoice_type-2">
                                            <input type="radio" name="invoice_type" id="invoice_type-2" class="form-input" value="2"/>
                                            Hoá đơn khác
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-sm-2">
                                        <label>Trạng thái </label>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <div class="radio">
                                            <label for="invoice_status-0">
                                                <input type="radio" name="invoice_status" id="invoice_status-0" class="form-input" value="0"/>
                                                Tất cả
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label for="invoice_status-1">
                                                <input type="radio" name="invoice_status" id="invoice_status-1" class="form-input" value="1"/>
                                                Lưu tạm thời
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label for="invoice_status-2">
                                                <input type="radio" name="invoice_status" id="invoice_status-2" class="form-input" value="2"/>
                                                Đã in - chưa duyệt
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label for="invoice_status-3">
                                                <input type="radio" name="invoice_status" id="invoice_status-3" class="form-input" value="3" checked/>
                                                Đã duyệt
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label for="invoice_status-4">
                                                <input type="radio" name="invoice_status" id="invoice_status-4" class="form-input" value="4"/>
                                                Huỷ
                                            </label>
                                        </div>
                                    </div>
                            </div>
                            <div class="row" style="height:10px"></div>
                            <div class="row">
                                <div class="form-group col-sm-2">
                                    <label>Trung tâm </label>
                                </div>
                                <div class="form-group col-sm-4">
                                    <select type="text" id="branch" class="form-input">
                                        <option value="0">[Tất cả trung tâm]</option>
                                        <option value="4">I Can Read Tô Hiến Thành</option>
                                        <option value="3">I Can Read Hoàng Quốc Việt</option>
                                    </select>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnModalExport">Xuất</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>