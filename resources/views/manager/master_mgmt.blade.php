@extends('layouts.app')

@section('css')
	<link href="{{ asset('css/manager.css') }}" rel="stylesheet">
@endsection

@section('content')
	<div class="container-fluid row justify-content-center">
		<!-- 就業時間 ------------------------------------------------->
		<div class="col-lg-3 card">
			<div class="card-header">
				就業時間
			</div>

			<div class="card-body">
				<table class="table table-striped">
					<tr>
						<th class="align-middle">開始時刻</th>
						<td>{{substr($fixed_time->start_time, 0, 5)}}</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#start_time_modal">変更</button></td>
					</tr>
					<tr>
						<th>終業時刻</th>
						<td>{{substr($fixed_time->left_time, 0, 5)}}</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#left_time_modal">変更</button></td>
					</tr>
					<tr>
						<th>休憩時間</th>
						<td>{{substr($fixed_time->rest_time, 0, 5)}}</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#rest_time_modal">変更</button></td>
					</tr>
				</table>
			</div>
		</div>
		<!----------------------------------------------end 就業時間 -->

		<!-- 部署一覧 ------------------------------------------------->
		<div class="col-lg-3 card">
			<div class="card-header">
				部署一覧
			</div>

			<div class="card-body">
				<table class="table table-striped">
					<tr class="align-middle">
						<th>ID</th><th>部署名</th><th></th>
						<th><button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#add_dept_modal">登録</button></th>
					</tr>
					@foreach ($departments as $department)
						<tr>
							<td>{{$department->id}}</td>
							<td>{{$department->name}}</td>
							<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#edit_dept_modal">変更</button></td>
							<td><button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">削除</button></td>
						</tr>
					@endforeach
				</table>
			</div>
		</div>
		<!----------------------------------------------end 部署一覧 -->

		<!-- 勤務区分一覧 --------------------------------------------->
		<div class="col-lg-3 card">
			<div class="card-header">
				勤務区分一覧
			</div>

			<div class="card-body">
				<table class="table table-striped">
					<tr class="align-middle">
						<th>ID</th><th>区分名</th><th></th>
						<th><button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#add_worktype_modal">登録</button></th>
					</tr>
					@foreach ($work_types as $work_type)
					<tr>
						<td>{{$work_type->id}}</td>
						<td>{{$work_type->name}}</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#edit_worktype_modal">変更</button></td>
						<td><button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">削除</button></td>
					</tr>
				@endforeach
				</table>
			</div>
		</div>
		<!------------------------------------------end 勤務区分一覧 -->

		<!-- 申請項目一覧 --------------------------------------------->
		<div class="col-lg-3 card">
			<div class="card-header">
				申請項目一覧
			</div>

			<div class="card-body">
				<table class="table table-striped">
					<tr class="align-middle">
						<th>ID</th><th>申請項目</th><th></th>
						<th><button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#add_worktype_modal">登録</button></th>
					</tr>
					@foreach ($app_types as $app_type)
						<tr>
							<td>{{$app_type->id}}</td>
							<td>{{$app_type->name}}</td>
							<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#edit_apptype_modal">変更</button></td>
							<td><button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">削除</button></td>
						</tr>
					@endforeach
				</table>
			</div>
		</div>
		<!------------------------------------------end 申請項目一覧 -->
	</div>
@endsection