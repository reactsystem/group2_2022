@extends('layouts.app')

@section('css')
	<link href="{{ asset('css/manager.css') }}" rel="stylesheet">
@endsection

@section('content')
	<div class="container-fluid row justify-content-center">
		<!--h1 class="text-center mt-3 mb-3">勤怠管理システム</h1-->
		
		<!-- 就業時間 -->
		<div class="col-lg-6 card">
			<div class="card-header">
				就業時間
			</div>

			<div class="card-body">
				<table class="table table-striped">
					<tr class="align-middle">
						<th>開始時刻</th>
						<td>
							@if (isset($fixed_time->start_time))
								{{$fixed_time->start_time}}
							@else
								09:30
							@endif
						</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#start_time_modal">変更</button></td>
					</tr>
					<tr class="align-middle">
						<th>終業時刻</th>
						<td>
							@if (isset($fixed_time->left_time))
								{{$fixed_time->left_time}}
							@else
								18:00
							@endif
						</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#left_time_modal">変更</button></td>
					</tr>
					<tr class="align-middle">
						<th>休憩時間</th>
						<td>
							@if (isset($fixed_time->rest_time))
								{{$fixed_time->rest_time}}
							@else
								00:45
							@endif
						</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#rest_time_modal">変更</button></td>
					</tr>
				</table>
			</div>
		</div>
		
		<!-- 部署一覧 -->
		<div class="col-lg-6 card">
			<div class="card-header">
				部署一覧
			</div>

			<div class="card-body">
				<table class="table table-striped">
					<tr class="align-middle">
						<th>ID</th><th>部署名</th><th></th>
						<th><button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#add_dept_modal">登録</button></th>
					</tr>
					<tr>
						<td>1</td>
						<td>営業部</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#edit_dept_modal">変更</button></td>
						<td><button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">削除</button></td>
					</tr>
					<tr>
						<td>2</td>
						<td>管理部</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#edit_dept_modal">変更</button></td>
						<td><button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">削除</button></td>
					</tr>
					<tr>
						<td>3</td>
						<td>開発部</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#edit_dept_modal">変更</button></td>
						<td><button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">削除</button></td>
					</tr>
				</table>
			</div>
		</div>
		
		<!-- 勤務区分一覧 -->
		<div class="col-lg-6 card">
			<div class="card-header">
				勤務区分一覧
			</div>

			<div class="card-body">
				<table class="table table-striped">
					<tr class="align-middle">
						<th>ID</th><th>部署名</th><th></th>
						<th><button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#add_worktype_modal">登録</button></th>
					</tr>
					<tr>
						<td>1</td>
						<td>出勤</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#edit_worktype_modal">変更</button></td>
						<td><button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">削除</button></td>
					</tr>
					<tr>
						<td>2</td>
						<td>欠勤</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#edit_worktype_modal">変更</button></td>
						<td><button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">削除</button></td>
					</tr>
					<tr>
						<td>3</td>
						<td>遅刻</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#edit_worktype_modal">変更</button></td>
						<td><button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">削除</button></td>
					</tr>
					<tr>
						<td>4</td>
						<td>早退</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#edit_worktype_modal">変更</button></td>
						<td><button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">削除</button></td>
					</tr>
					<tr>
						<td>5</td>
						<td>有給休暇</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#edit_worktype_modal">変更</button></td>
						<td><button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">削除</button></td>
					</tr>
					<tr>
						<td>6</td>
						<td>特別休暇</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#edit_worktype_modal">変更</button></td>
						<td><button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">削除</button></td>
					</tr>
				</table>
			</div>
		</div>
		
		<!-- 申請項目一覧 -->
		<div class="col-lg-6 card">
			<div class="card-header">
				申請項目一覧
			</div>

			<div class="card-body">
				<table class="table table-striped">
					<tr class="align-middle">
						<th>ID</th><th>申請項目</th><th></th>
						<th><button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#add_worktype_modal">登録</button></th>
					</tr>
					<tr>
						<td>1</td>
						<td>有給休暇</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#edit_apptype_modal">変更</button></td>
						<td><button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">削除</button></td>
					</tr>
					<tr>
						<td>2</td>
						<td>特別休暇</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#edit_apptype_modal">変更</button></td>
						<td><button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">削除</button></td>
					</tr>
					<tr>
						<td>3</td>
						<td>休日出勤</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#edit_apptype_modal">変更</button></td>
						<td><button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">削除</button></td>
					</tr>
					<tr>
						<td>4</td>
						<td>時間外勤務</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#edit_apptype_modal">変更</button></td>
						<td><button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">削除</button></td>
					</tr>
					<tr>
						<td>5</td>
						<td>打刻時間修正</td>
						<td><button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#edit_apptype_modal">変更</button></td>
						<td><button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#delete_modal">削除</button></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
@endsection