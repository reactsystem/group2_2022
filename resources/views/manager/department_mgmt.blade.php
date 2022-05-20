@extends('layouts.app')

@section('css')
	<link href="{{ asset('css/department_mgmt.css') }}" rel="stylesheet">
@endsection

@section('content')
	<div class="container-fluid row justify-content-center">
		<div class="col-lg-12 card">
			<div class="card-header bg-light">
				{{$dept->old}}
				<!-- 絞り込み条件選択フォーム --------------------------------->
				<form action="{{route('mgmt.dept.post')}}" method="POST" id="form-cond" class="form-inline">
					@csrf
					<!-- ページ表示条件 -->
					<input type="hidden" name="disp_limit" value="{{$disp_limit}}">

					<!-- 部署選択 -->
					<input type="hidden" name="department" value="{{$dept->id}}">
					<div class="dropdown">
						<button type="button" id="dpdn-dept" class="btn btn-light dropdown-toggle"
							data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							{{$dept->name}}
						</button>
						<div class="dropdown-menu" aria-labelledby="dpdn-dept">
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
						<input type="text" name="date" value="{{$date->copy()->toDateString()}}"
							class="form-control datetimepicker-input" data-target="#date-picker">
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
						<button type="button" class="btn btn-outline-secondary"
							data-toggle="modal" data-target="#modal-export">
							{{$date->month}}月分集計ファイル出力
						</button>
					</div>
				</form>
				<!------------------------------end 絞り込み条件選択フォーム -->
			</div>

			<div class="card-body">
				<ul class="list-group list-group-flush">
					<!-- ページ表示条件 ----------------------------------------------->
					<li class="list-group-item">
						<form action="{{route('mgmt.dept.post')}}" method="POST" class="form-inline">
							@csrf
							<!-- 絞り込み条件 -->
							<input type="hidden" name="department" value="{{$dept->id}}">
							<input type="hidden" name="date" value="{{$date->copy()->toDateString()}}">
							
							<!-- ページネーション -->
							{{$work_times->appends([
									'date' => $date->copy()->toDateString(),
									'department' => $dept->id,
									'disp_limit' => $disp_limit,
								])
								->links()}}

							<!-- 隙間を開けるための記述 -->
							<div class="col-sm-1"></div>

							<!-- 表示件数 -->
							<input type="hidden" name="disp_limit" value="{{$disp_limit}}">
							<label>表示件数：</label>
							<div class="dropdown col-md-6">
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
						// jsに分けて書いたら見つからないと怒られたのでとりあえずここに書いてます。
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
						<table class="table table-striped table-bordered">
							<thead class="thead-light">
								<tr><th></th><th>名前</th><th>勤務区分</th><th>開始</th><th>終了</th><th>休憩時間</th><th>労働時間</th><th>時間外</th>
									<th class="col-sm-4">メモ</th>
								</tr>
							</thead>
		
							@foreach ($work_times as $index => $work_time)
								<tr>
									<td>{{$index + 1}}</td>
									<td><a href="{{route('mgmt.personal', ['user_id' => $work_time->user->id,])}}">{{$work_time->user->name}}</a></td>
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

						<!-- ページネーション -->
						{{$work_times->appends([
								'date' => $date->copy()->toDateString(),
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

	<!-- Modal ---------------------------------------------------->
	<!-- 月次部署別勤怠集計CSV出力フォーム -->
	<div class="modal fade" id="modal-export" tabindex="-1" aria-labelledby="label-fixed" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<form action="{{route('mgmt.export')}}" method="POST" id="form-export">
					@csrf
					<!-- 絞り込み条件 -->
					<input type="hidden" name="department" value="{{$dept->id}}">
					<input type="hidden" name="date" value="{{$date->copy()->toDateString()}}">
					<!-- ページ表示条件 -->
					<input type="hidden" name="disp_limit" value="{{$disp_limit}}">

					<div class="modal-header">
						<h5 class="modal-title">月次部署別勤怠集計CSV出力</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<div class="row mb-3">
							<!-- ファイル名 -->
							<div class="col-md-6 right">
								<div class="input-group">
									<input type="text" name="file_name" value="{{$date->format('Ym')}}_{{$dept->name}}_月次集計" class="form-control text-right">
									<div class="input-group-append">
										<span class="input-group-text">.csv</span>
									</div>
								</div>
							</div>
							<button type="submit" class="col-md-auto mr-3 btn btn-primary">出力</button>
						</div>

						<!-- csv プレビュー -->
						<table class="table table-striped table-bordered">
							<tr><th>社員番号</th><th>社員名</th><th>出勤日数</th><th>労働時間</th><th>時間外労働時間</th><th>有給休暇取得日数</th></tr>
							@foreach ($csv as $data)
								<tr>
									@foreach ($data as $item)
										<td>{{$item}}</td>
									@endforeach
								</tr>
							@endforeach
						</table>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
						<button type="button" id="btn-export" class="btn btn-primary">出力</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-------------------------------------------------end Modal -->
@endsection

@section('js')
	<script src="{{asset('js/department_mgmt.js')}}"></script>
@endsection