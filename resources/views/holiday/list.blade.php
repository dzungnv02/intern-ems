@extends('layouts.master')
@section('title')
	Danh sách Ngày nghỉ lễ
@endsection
@section('breadcrumb')
	<li class="active">danh sách ngày nghỉ</li>
@endsection
@section('content')
	<div><button class="btn btn-info add-holiday">Thêm Ngày nghỉ</button></div><br>
	<div class="card-body table-reponsive">
		<table class="table table-bordered table-striped" id="list-holiday">
			<thead>
				<tr>
					<th>STT</th>
					<th data-field="holiday">Ngày Nghỉ Lễ</th>
					<th>Action</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>STT</th>
					<th data-field="holiday">Ngày Nghỉ Lễ</th>
					<th>Action</th>
				</tr>
			</tfoot>
		</table>
	</div></br>

	<div class="modal fade" id="add-holiday">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Thêm ngày nghỉ</h4>
				</div>
				<div class="modal-body">
					<form id="form-add-holiday" method="POST" role="form">
						@csrf
						<div class="form-group">
							<label for="">Chọn ngày</label>
							<input readonly type="text" class="form-control" id="holiday">
						</div>
						
						<button id="store-holiday" type="submit" class="btn btn-primary">Save</button>
					</form>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

@endsection