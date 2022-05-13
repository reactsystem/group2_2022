@extends('layouts.app')

@section('css')
	<link href="{{ asset('css/manager.css') }}" rel="stylesheet">
@endsection

@section('content')
	<div class="container-fluid row justify-content-center">
		<div class="col-lg-12 card">
			<div class="card-header bg-light">
				<!-- 絞り込み条件選択フォーム --------------------------------->
				<form action="" method="POST" id="cond-form" class="form-inline">
					@csrf
					<!-- ページ表示条件 -->
					<input type="hidden" name="disp_limit" value="{{$disp_limit}}">

					<!-- 部署選択 -->
					<input type="hidden" name="department" value="{{$dept->id}}">
					<div class="dropdown">
						<button type="button" id="dropdown1" class="btn btn-light dropdown-toggle"
							data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							{{$dept->name}}
						</button>
						<div class="dropdown-menu" aria-labelledby="dropdown1">
							@foreach ($departments as $department)
								@if ($department->id === $dept->id)
									<button type="submit" name="department" value="{{$department->id}}" class="dropdown-item active">{{$department->name}}</button>
								@else
									<button type="submit" name="department" value="{{$department->id}}" class="dropdown-item">{{$department->name}}</button>
								@endif
							@endforeach
						</div>
					</div>

					<!-- 日付選択 -->
					<div class="input-group date w-auto" id="date-picker" data-target-input="nearest">
						<input type="text" name="date" value="{{$date->toDateString()}}" class="form-control datetimepicker-input" data-target="#date-picker">
						<div class="input-group-append" data-target="#date-picker" data-toggle="datetimepicker">
							<div class="input-group-text">
								<i class="fa fa-calendar"></i>
							</div>
						</div>
					</div>
					<button type="submit" name="date" value="{{$date->copy()->subDay()}}" class="btn btn-light">< 前日</button>
					<button type="submit" name="date" value="{{$date->copy()->addDay()}}" class="btn btn-light">翌日 ></button>

					<div class="right">
						<!-- 更新ボタン -->
						<button type="submit" class="btn btn-outline-primary">更新</button>

						<!-- csv出力ボタン -->
						<button type="button" class="btn btn-outline-secondary">{{$date->month}}月分集計ファイル出力</button>
					</div>
				</form>
				<!------------------------------end 絞り込み条件選択フォーム -->
			</div>

			<div class="card-body">
				<ul class="list-group list-group-flush">
					<!-- ページ表示条件 ----------------------------------------------->
					<li class="list-group-item">
						<form action="" method="POST" id="disp-form" class="form-inline">
							@csrf
							<!-- 絞り込み条件 -->
							<input type="hidden" name="department" value="{{$dept->id}}">
							<input type="hidden" name="date" value="{{$date->toDateString()}}">
							
							<!-- ページネーション -->
							{{$work_times->appends([
									'date' => $date->toDateString(),
									'department' => $dept->id,
									'disp_limit' => $disp_limit,
								])
								->links()}}

							<div class="col-sm-1"></div>

							<!-- 表示件数 -->
							<input type="hidden" name="disp_limit" value="{{$disp_limit}}">
							<label>表示件数：</label>
							<div class="dropdown">
								<button type="button" id="dpdn-limit" class="btn btn-sm page-link text-dark dropdown-toggle"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									@if (isset($disp_limit))
										{{$disp_limit}}件
									@else
										全て
									@endif
								</button>
								<div class="dropdown-menu" aria-labelledby="dpdn-limit">
									<button type="submit" name="disp_limit" value="" class="dropdown-item">全て</button>
									<button type="submit" name="disp_limit" value="5" class="dropdown-item">5件</button>
									<button type="submit" name="disp_limit" value="10" class="dropdown-item">10件</button>
									<button type="submit" name="disp_limit" value="20" class="dropdown-item">20件</button>
									<button type="submit" name="disp_limit" value="50" class="dropdown-item">50件</button>
									<button type="submit" name="disp_limit" value="100" class="dropdown-item">100件</button>
								</div>
							</div>
						</form>
					</li>
					<!----------------------------------------end 表示ページ条件 -->

					<!-- 部署別勤務表 --------------------------------------------->
					<?php
						// 労働時間計算関数
						function calcWorkTime($start_time, $left_time, $rest_time)
						{
							$start = new DateTime($start_time);
							$left = new DateTime($left_time);
							$rest = new DateTime($rest_time);

							$work = $left->diff($start);
							$work = new DateTime($work->h.':'.$work->i.':00');
							$work = $work->diff($rest);

							return $work;
						}
					?>
					<li class="list-group-item">
						<table class="table table-striped  table-bordered">
							<thead class="thead-light">
								<tr><th></th><th>名前</th><th>勤務区分</th><th>開始</th><th>終了</th><th>休憩時間</th><th>労働時間</th><th>時間外</th>
									<th class="col-sm-4">メモ</th>
								</tr>
							</thead>
		
							@foreach ($work_times as $index => $work_time)
								<tr>
									<td>{{$index + 1}}</td>
									<td><a href="{{route('mgmt.personal')}}">{{$work_time->user->name}}</a></td>
									<td>{{$work_time->workType->name}}</td>
									<td>{{substr($work_time->start_time, 0, 5)}}</td>
									<td>{{substr($work_time->left_time, 0, 5)}}</td>
									<td>{{substr($work_time->rest_time, 0, 5)}}</td>
									<td><?php
										// 欠勤、休暇の場合
										if(!isset($work_time->start_time) || !isset($work_time->left_time) || !isset($work_time->rest_time))
										{
											echo '00:00';
										}
										else
										{
											// 丸め誤差
											$start = $work_time->start_time;
											$left = $work_time->left_time;
											if(strtotime($fixed_time->start_time) > strtotime($start))
												{	$start = $fixed_time->start_time;	}
											if(strtotime('00:00:00') < strtotime($work_time->over_time))
												{	$left = $fixed_time->left_time;	}
		
											// 労働時間計算
											$work = calcWorkTime($start, $left, $work_time->rest_time);
		
											echo $work->format('%H:%i');
										}	?>
									</td>
									<td>{{substr($work_time->over_time, 0, 5)}}</td>
									<td>{{$work_time->discription}}</td>
								</tr>
							@endforeach
						</table>
						{{$work_times->appends([
								'date' => $date->toDateString(),
								'department' => $dept->id,
								'disp_limit' => $disp_limit,
							])
							->links()}}
					</li>
					<!------------------------------------------end 部署別勤務表 -->
				</ul>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<script>
		$(document).ready(function () {
			// DatePicker
			$('#date-picker').datetimepicker(
			{
				locale: 'ja',
				dayViewHeaderFormat: 'YYYY年M月',
				format: 'YYYY/MM/DD'
			});
			$('#date-picker').on('change.datetimepicker', function()
			{
				$('#cond-form').submit();
			});
		});
	</script>
@endsection