@extends('layouts.app')

@section('css')
	<link href="{{ asset('css/manager.css') }}" rel="stylesheet">
@endsection

@section('js')
	<link href="{{ asset('js/manager.js') }}" rel="stylesheet">
@endsection

@section('content')
	<div class="container-fluid row justify-content-center">
		<div class="col-lg-12 card">
			<div class="card-header bg-light flex">
				<!-- 部署選択フォーム ----------------------------------------->
				<div class="dropdown">
					<button type="button" id="dropdown1"
						class="btn btn-light dropdown-toggle"
						data-toggle="dropdown"
						aria-haspopup="true"
						aria-expanded="false">
						{{$dept->name}}
					</button>
					<div class="dropdown-menu" aria-labelledby="dropdown1">
						@foreach ($departments as $department)
							@if ($department->id === $dept->id)
								<a class="dropdown-item active" href="">{{$department->name}}</a>
							@else
								<a class="dropdown-item" href="">{{$department->name}}</a>
							@endif
						@endforeach
					</div>
				</div>
				<!--------------------------------------end 部署選択フォーム -->

				<!-- 日付選択フォーム ----------------------------------------->
				<div class="input-group date w-auto" id="datePicker" data-target-input="nearest">
					<input type="text" name="date" value="{{$date->toDateString()}}" class="form-control datetimepicker-input" data-target="#datePicker">
					<div class="input-group-append" data-target="#datePicker" data-toggle="datetimepicker">
						<div class="input-group-text">
							<i class="fa fa-calendar"></i>
						</div>
					</div>
				</div>

				<form action="">
					@csrf
					<input type="hidden" name="date" value="{{$date->subDay()}}">
					<input type="submit" value="< 前日" class="btn">
				</form>
				<form action="">
					@csrf
					<input type="hidden" name="date" value="{{$date->addDay(2)}}">
					<input type="submit" value="翌日 >" class="btn">
				</form>

				<div class="right">
					<a class="btn btn-outline-primary" href="">更新</a>
					<button type="button" class="btn btn-outline-secondary">{{$date->month}}月分集計ファイル出力</button>
				</div>
				<!--------------------------------------end 日付選択フォーム -->
			</div>

			<div class="card-body">
				<!-- 部署別勤務表 --------------------------------------------->
				<table class="table table-striped">
					<thead class="thead-light">
						<tr><th></th><th>名前</th><th>勤務区分</th><th>開始</th><th>終了</th><th>休憩時間</th><th>労働時間</th><th>時間外</th><th>メモ</th></tr>
					</thead>

					@foreach ($work_times as $index => $work_time)
						<tr>
							<td>{{$index + 1}}</td>
							<td><a href="">{{$work_time->user->name}}</a></td>
							<td>{{$work_time->workType->name}}</td>
							<td>{{$work_time->start_time}}</td>
							<td>{{$work_time->left_time}}</td>
							<td>{{$work_time->rest_time}}</td>
							<td>{{$work_time->over_time}}</td>
							<td>{{$work_time->discription}}</td>
						</tr>
					@endforeach
				</table>
				<!------------------------------------------end 部署別勤務表 -->
			</div>
		</div>
	</div>
@endsection