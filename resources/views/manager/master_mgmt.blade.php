@extends('layouts.app')

@section('css')
	<link href="{{ asset('css/master.css') }}" rel="stylesheet">
@endsection

@section('content')
	<div class="container-fluid row justify-content-center">
		<div class="col-lg-2">
			<!-- scrollspy ------------------------------------------------>
			<div id="scroll-menu" class="list-group sticky-top">
				<a href="#fixed" class="list-group-item list-group-item-action">就業時間</a>
				<a href="#department" class="list-group-item list-group-item-action">部署一覧</a>
				<a href="#work-type" class="list-group-item list-group-item-action">勤務区分一覧</a>
				<a href="#app-type" class="list-group-item list-group-item-action">申請項目一覧</a>
			</div>
			<!---------------------------------------------end scrollspy -->
		</div>

		<div class="col-lg-4">
			<!-- 就業時間 ------------------------------------------------->
			<div class="row card" id="fixed">
				<div class="card-header">
					就業時間
				</div>

				<div class="card-body">
					<table class="table">
						<tr class="table-info">
							<th class="col-sm-3">開始</th>
							<th class="col-sm-3">終了</th>
							<th class="col-sm-3">休憩時間</th>
							<th class="col-sm-3"></th>
						</tr>
						<tr>
							<td>{{date('H:i', strtotime($fixed_time->start_time))}}</td>
							<td>{{date('H:i', strtotime($fixed_time->left_time))}}</td>
							<td>{{date('H:i', strtotime($fixed_time->rest_time))}}</td>
							<td><button type="button" class="btn btn-outline-secondary"
									data-toggle="modal" data-target="#modal-edit-fixed"
									data-id="{{$fixed_time->id}}"
									data-start="{{date('H:i', strtotime($fixed_time->start_time))}}"
									data-left="{{date('H:i', strtotime($fixed_time->left_time))}}"
									data-rest="{{date('H:i', strtotime($fixed_time->rest_time))}}">
									変更
								</button>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<!----------------------------------------------end 就業時間 -->

			<!-- 部署一覧 ------------------------------------------------->
			<div class="row card" id="department">
				<div class="card-header">
					部署一覧
				</div>

				<div class="card-body">
					<table class="table">
						<tr class="table-info">
							<th class="col-sm-1">ID</th>
							<th class="col-sm-5">部署名</th>
							<th class="col-sm-3"></th>
							<th class="col-sm-3">
								<button type="button" class="btn btn-outline-primary"
									data-toggle="modal" data-target="#modal-add"
									data-title="部署"
									data-label="部署名"
									data-table="department">
									登録
								</button>
							</th>
						</tr>
						@foreach ($departments as $department)
							<tr>
								<td>{{$department->id}}</td>
								<td>{{$department->name}}</td>
								<td><button type="button" class="btn btn-outline-secondary"
										data-toggle="modal" data-target="#modal-edit"
										data-title="部署"
										data-label="部署名"
										data-table="department"
										data-id="{{$department->id}}"
										data-name="{{$department->name}}">
										変更
									</button>
								</td>
								<td><button type="button" class="btn btn-outline-danger"
										data-toggle="modal" data-target="#modal-del"
										data-title="部署"
										data-label="部署"
										data-table="department"
										data-id="{{$department->id}}"
										data-name="{{$department->name}}">
										削除
									</button>
								</td>
							</tr>
						@endforeach
					</table>
				</div>
			</div>
			<!----------------------------------------------end 部署一覧 -->

			<!-- 勤務区分一覧 --------------------------------------------->
			<div class="row card" id="work-type">
				<div class="card-header">
					勤務区分一覧
				</div>

				<div class="card-body">
					<table class="table">
						<tr class="table-info">
							<th class="col-sm-1">ID</th>
							<th class="col-sm-5">区分名</th>
							<th class="col-sm-3"></th>
							<th class="col-sm-3">
								<button type="button" class="btn btn-outline-primary"
									data-toggle="modal" data-target="#modal-add"
									data-title="勤務区分"
									data-label="区分名"
									data-table="work_type">
									登録
								</button>
							</th>
						</tr>
						@foreach ($work_types as $work_type)
						<tr>
							<td>{{$work_type->id}}</td>
							<td>{{$work_type->name}}</td>

							<!-- work_type->id の 1-7 は変更、削除不可 -->
							@if ($work_type->id > 7)
								<td><button type="button" class="btn btn-outline-secondary"
										data-toggle="modal" data-target="#modal-edit"
										data-title="勤務区分"
										data-label="区分名"
										data-table="work_type"
										data-id="{{$work_type->id}}"
										data-name="{{$work_type->name}}">
										変更
									</button>
								</td>
								<td><button type="button" class="btn btn-outline-danger"
										data-toggle="modal" data-target="#modal-del"
										data-title="勤務区分"
										data-label="勤務区分"
										data-table="work_type"
										data-id="{{$work_type->id}}"
										data-name="{{$work_type->name}}">
										削除
									</button>
								</td>
							@else
								<td></td>
								<td></td>
							@endif
						</tr>
					@endforeach
					</table>
				</div>
			</div>
			<!------------------------------------------end 勤務区分一覧 -->

			<!-- 申請項目一覧 --------------------------------------------->
			<div class="row card" id="app-type">
				<div class="card-header">
					申請項目一覧
				</div>

				<div class="card-body">
					<table class="table">
						<tr class="table-info">
							<th class="col-sm-1">ID</th>
							<th class="col-sm-5">申請項目</th>
							<th class="col-sm-3"></th>
							<th class="col-sm-3">
								<button type="button" class="btn btn-outline-primary"
									data-toggle="modal" data-target="#modal-add-app">
									登録
								</button>
							</th>
						</tr>
						@foreach ($app_types as $app_type)
							<tr>
								<td>{{$app_type->id}}</td>
								<td>{{$app_type->name}}</td>

								<!-- app_type->id の 1-5 は変更、削除不可 -->
								@if ($app_type->id > 5)
								<td><button type="button" class="btn btn-outline-secondary"
										data-toggle="modal" data-target="#modal-edit-app"
										data-id="{{$app_type->id}}"
										data-name="{{$app_type->name}}"
										data-work-type="{{$app_type->work_type_id}}">
										変更
									</button>
								</td>
								<td><button type="button" class="btn btn-outline-danger"
										data-toggle="modal" data-target="#modal-del"
										data-title="申請項目"
										data-label="申請項目"
										data-table="application"
										data-id="{{$app_type->id}}"
										data-name="{{$app_type->name}}">
										削除
									</button>
								</td>
								@else
									<td></td>
									<td></td>
								@endif
							</tr>
						@endforeach
					</table>
				</div>
			</div>
			<!------------------------------------------end 申請項目一覧 -->
		</div>
	</div>

	<!-- Modal ---------------------------------------------------->
	<!-- 就業時間編集フォーム -->
	<div class="modal fade" id="modal-edit-fixed" tabindex="-1" aria-labelledby="label-fixed" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form action="{{route('master.update')}}" method="POST">
					@csrf
					<div class="modal-header">
						<h5 class="modal-title">就業時間編集</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<input type="hidden" name="table" value="fixed_time">
						<input type="hidden" name="id" id="fixed-id">
						<div class="row">
							<label for="fixed-start" class="col-md-4 col-form-label text-md-end">始業時間</label>
							<div class="col-md-4">
								<div class="input-group date" id="start-time-picker" data-target-input="nearest">
									<input type="time" name="start_time" id="fixed-start" class="icon-del form-control datetimepicker-input" data-target="#start-time-picker" required>
									<div class="input-group-append" data-target="#start-time-picker" data-toggle="datetimepicker">
										<div class="input-group-text"><i class="fa fa-clock"></i></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<label for="fixed-left" class="col-md-4 col-form-label text-md-end">終業時間</label>
							<div class="col-md-4">
								<div class="input-group date" id="left-time-picker" data-target-input="nearest">
									<input type="time" name="left_time" id="fixed-left" class="icon-del form-control datetimepicker-input" data-target="#left-time-picker" required>
									<div class="input-group-text" data-target="#left-time-picker" data-toggle="datetimepicker">
										<i class="fa fa-clock"></i>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<label for="fixed-rest" class="col-md-4 col-form-label text-md-end">休憩時間</label>
							<div class="col-md-4">
								<div class="input-group date" id="rest-time-picker" data-target-input="nearest">
									<input type="time" name="rest_time" id="fixed-rest" class="icon-del form-control datetimepicker-input" data-target="#rest-time-picker" required>
									<div class="input-group-append" data-target="#rest-time-picker" data-toggle="datetimepicker">
										<div class="input-group-text"><i class="fa fa-clock"></i></div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-secondary">変更</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- 申請項目追加フォーム -->
	<div class="modal fade" id="modal-add-app" tabindex="-1" aria-labelledby="label-add" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form action="{{route('master.create')}}" method="POST">
					@csrf
					<div class="modal-header">
						<h5 class="modal-title">申請項目追加</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<div class="row">
							<input type="hidden" name="table" value="application">

							<label for="add-app-name" class="col-md-4 col-form-label text-md-end">申請項目</label>
							<div class="col-md-6">
								<input type="text" name="name" id="add-app-name" class="form-control" required autofocus>
								<div class="nameError d-none" role="alert">
									<strong class="nameErrorMsg text-danger"></strong>
								</div>
							</div>

							<label for="select-work-type" class="col-md-4 col-form-label text-md-end">勤務区分</label>
							<div class="col-md-6">
								<select name="work_type_id" id="select-work-type" class="form-select" required>
									<option hidden value="">選択してください</option>
									@foreach($work_types as $work_type)
										<option value="{{$work_type->id}}">{{$work_type->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">登録</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- 申請項目編集フォーム -->
	<div class="modal fade" id="modal-edit-app" tabindex="-1" aria-labelledby="label-edit" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form action="{{route('master.update')}}" method="POST">
					@csrf
					<div class="modal-header">
						<h5 class="modal-title" id="label-edit">申請項目編集</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<div class="row">
							<input type="hidden" name="table" value="application">
							<input type="hidden" name="id" id="edit-app-id">

							<label for="edit-app-name" id="edit-label" class="col-md-4 col-form-label text-md-end">申請項目</label>
							<div class="col-md-6">
								<input type="text" name="name" id="edit-app-name" class="form-control" required>
								<div class="nameError d-none" role="alert">
									<strong class="nameErrorMsg text-danger"></strong>
								</div>
							</div>

							<label for="select-work-type" class="col-md-4 col-form-label text-md-end">勤務区分</label>
							<div class="col-md-6">
								<select name="work_type_id" id="select-work-type" class="form-select" required>
									@foreach($work_types as $work_type)
										<option value="{{$work_type->id}}">{{$work_type->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-secondary">変更</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- 追加フォーム -->
	<div class="modal fade" id="modal-add" tabindex="-1" aria-labelledby="label-add" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form action="{{route('master.create')}}" method="POST">
					@csrf
					<div class="modal-header">
						<h5 class="modal-title" id="label-add">Title</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<div class="row">
							<label for="add-name" id="add-label" class="col-md-4 col-form-label text-md-end">Name</label>
							<div class="col-md-6">
								<input type="hidden" name="table" id="add-table">
								<input type="text" name="name" id="add-name" class="form-control" required autofocus>
								<div class="nameError d-none" role="alert">
									<strong class="nameErrorMsg text-danger"></strong>
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">登録</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- 編集フォーム -->
	<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="label-edit" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form action="{{route('master.update')}}" method="POST">
					@csrf
					<div class="modal-header">
						<h5 class="modal-title" id="label-edit">Title</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<div class="row">
							<label for="edit-name" id="edit-label" class="col-md-4 col-form-label text-md-end">Name</label>
							<div class="col-md-6">
								<input type="hidden" name="table" id="edit-table">
								<input type="hidden" name="id" id="edit-id">
								<input type="text" name="name" id="edit-name" class="form-control" required>
								<div class="nameError d-none" role="alert">
									<strong class="nameErrorMsg text-danger"></strong>
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-secondary">変更</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- 削除フォーム -->
	<div class="modal fade" id="modal-del" tabindex="-1" aria-labelledby="label-del" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form action="{{route('master.delete')}}" method="POST">
					@csrf
					<div class="modal-header">
						<h5 class="modal-title" id="label-edit">Title</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<div class="row">
							<label id="del-label" class="text-center">Message</label>
							<input type="hidden" name="table" id="del-table">
							<input type="hidden" name="id" id="del-id">
						</div>
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-danger">削除</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-------------------------------------------------end Modal -->
@endsection

@section('js')
	<script src="{{asset('js/master.js')}}"></script>
@endsection