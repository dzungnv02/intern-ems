 @extends('layouts.master')
 @section('header')
 @endsection
 @section('title')
 Hoá đơn thu tiền
 @endsection
 @section('content')
 <form action="" class="form-horizontal" method="post" id="frm_invoice">
	<div class="box-body">
		<div class="form-group">
			<label for="invoice_type" class="col-sm-2 col-form-label">Loại phí *</label>
			<div class="col-sm-8">
				<label for="invoice_type_tuition"><input type="radio" name="invoice_type" value="TUITION_FEE" id="invoice_type_tuition" placeholder="Học phí" checked> Học phí </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<label for="invoice_type_other"><input type="radio" name="invoice_type" value="OTHER_FEE" id="invoice_type_other" placeholder="Phí khác"> Loại phí khác </label>
			</div>
		</div>
		<div class="form-group">
			<label for="student" class="col-sm-2 col-form-label">Học viên *</label>
			<div class="col-sm-8">
				<select class="form-control select2" name="student_id" id="student_id" style="width: 100%;">
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="class" class="col-sm-2 col-form-label">Lớp *</label>
			<div class="col-sm-8">
				<select class="form-control select2" name="class_id" id="class_id" style="width: 100%;">
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="reason" class="col-sm-2 col-form-label"> Lý do nộp *</label>
			<div class="col-sm-8">
				<textarea class="form-control" id="reason" placeholder="Lý do nộp phí"></textarea>
			</div>
		</div>
		<div class="form-group" id="group_date_range">
			<label class="col-sm-2 col-form-label">Thời gian </label>
			<div class="col-sm-8">
				<label for="start_date" class="col-form-label">Từ ngày:
					<div class="input-group date">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input type="text" id="start_date" class="form-control pull-right">
					</div>
				</label>
				<label for="start_date" class="col-form-label">Đến ngày:
					<div class="input-group date">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input type="text" id="end_date" class="form-control pull-right" >
					</div>
				</label>
			</div>
		</div>
		<div class="form-group" id="group_duration">
			<label class="col-sm-2 col-form-label">Số buổi học: </label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="duration" placeholder="Số buổi học">
			</div>
		</div>
		<div class="form-group" id="group_amount">
			<label class="col-sm-2 col-form-label">Tổng số tiền: </label>
			<div class="col-sm-8">
				<div class="input-group input-group-sm">
					<span class="input-group-addon">VND</span>
					<input type="text" class="form-control" id="amount" placeholder="Tổng số tiền">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 col-form-label">Người đóng tiền: </label>
			<div class="col-sm-8">
				<input type="text" class="form-control" id="payer" placeholder="payer">
			</div>
		</div>
	</div>
	<div class="box-footer">
		<div class="form-group">
			<div class="col-sm-2">
				<button class="btn btn-primary" id="btn_print" type="button">In hoá đơn</button>
			</div>
			<div class="col-sm-2">
				<button type="reset" class="btn btn-warning" id="resetForm">Làm mới</button>
			</div>
		</div>
	</div>
	
 </form>
 @endsection
 @section('footer')
 <script>
 	$('SELECT.select2[id="student_id"]').select2();
	$('SELECT.select2[id="class_id"]').select2();
 </script>
 @endsection