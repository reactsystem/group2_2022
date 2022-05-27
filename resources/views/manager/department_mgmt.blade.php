@extends('layouts.app')

@section('css')
	<link href="{{ asset('css/department_mgmt.css') }}" rel="stylesheet">
@endsection

@section('content')
	<div class="container-fluid row justify-content-center">
		<div class="col-sm-10">
			<!-- 絞り込み条件選択フォーム --------------------------------->
			<div class="row align-items-top mb-3">
				<form action="{{route('mgmt.dept.post')}}" method="POST" id="form-cond" class="form-inline">
					@csrf
					<!-- ページ表示条件 -->
					<input type="hidden" name="disp_limit" value="{{$disp_limit}}">

					<!-- 部署選択 -->
					<input type="hidden" name="department" value="{{$dept->id}}">
					<div class="dropdown">
						<button type="button" id="dpdn-dept" class="btn btn-outline-default dropdown-toggle"
							data-bs-toggle="dropdown" aria-expanded="false">
							{{$dept->name}}
						</button>
						<ul class="dropdown-menu" aria-labelledby="dpdn-dept">
							<li><button type="submit" name="department" value="0"
									class="dropdown-item @if (0 === $dept->id) active @endif">
									全て
								</button>
							</li>
							@foreach ($departments as $department)
								<li><button type="submit" name="department" value="{{$department->id}}"
										class="dropdown-item @if ($department->id === $dept->id) active @endif">
										{{$department->name}}
									</button>
								</li>
							@endforeach
						</ul>
					</div>

					<!-- 日付選択 -->
					<div class="input-group date col-sm-3" id="date-picker" data-target-input="nearest">
						<input type="text" name="date" value="{{$date->copy()->toDateString()}}"
							class="form-control datetimepicker-input" data-target="#date-picker">
						<div class="input-group-append" data-target="#date-picker" data-toggle="datetimepicker">
							<div class="input-group-text">
								<i class="fa fa-calendar"></i>
							</div>
						</div>
					</div>
					<button type="submit" name="date" value="{{$date->copy()->subDay()}}" class="btn btn-outline-default mr-1">< 前日</button>
					<button type="submit" name="date" value="{{$date->copy()->addDay()}}" class="btn btn-outline-default">翌日 ></button>

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
			</div>
			<!------------------------------end 絞り込み条件選択フォーム -->

			<!-- ページ表示条件 ----------------------------------------------->
			<div class="row align-items-top" style="height: 48px">
				<div class="col-sm-2">
					<form action="{{route('mgmt.dept.post')}}" method="POST" class="form-inline">
						@csrf
						<!-- 絞り込み条件 -->
						<input type="hidden" name="department" value="{{$dept->id}}">
						<input type="hidden" name="date" value="{{$date->copy()->toDateString()}}">

						<!-- 表示件数 -->
						<input type="hidden" name="disp_limit" value="{{$disp_limit}}">
						<label>表示件数：</label>
						<button type="button" id="dpdn-limit" class="btn btn-sm btn-outline-default dropdown-toggle col-sm-5"
							data-toggle="dropdown" aria-expanded="false">
							@if (isset($disp_limit))
								{{$disp_limit}}件
							@else
								全て
							@endif
						</button>
						<ul class="dropdown-menu" aria-labelledby="dpdn-limit">
							<li><button type="submit" name="disp_limit" value=""
									class="dropdown-item @if (null === $disp_limit) active @endif">全て</button></li>
							<li><button type="submit" name="disp_limit" value="5"
									class="dropdown-item @if ("5" === $disp_limit) active @endif">5件</button></li>
							<li><button type="submit" name="disp_limit" value="10"
									class="dropdown-item @if ("10" === $disp_limit) active @endif">10件</button></li>
							<li><button type="submit" name="disp_limit" value="20"
									class="dropdown-item @if ("20" === $disp_limit) active @endif">20件</button></li>
							<li><button type="submit" name="disp_limit" value="50"
									class="dropdown-item @if ("50" === $disp_limit) active @endif">50件</button></li>
							<li><button type="submit" name="disp_limit" value="100"
									class="dropdown-item @if ("100" === $disp_limit) active @endif">100件</button></li>
						</ul>
					</form>
				</div>

				<!-- ページネーション -->
				<span class="col-sm-2">
					{{$users->appends([
						'date' => $date->copy()->toDateString(),
						'department' => $dept->id,
						'disp_limit' => $disp_limit,
						])
						->links()}}
				</span>
			</div>
			<!----------------------------------------end 表示ページ条件 -->

			<!-- 部署別勤務表 --------------------------------------------->
			@php
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

					return $work->format('%H:%i');
				}
			@endphp
			
			<table class="table table-bordered table-sm">
				<tr class="table-info text-center">
					<th></th><th>名前</th><th>勤務区分</th>
					<th class="col-sm-1">開始</th>
					<th class="col-sm-1">終了</th>
					<th>休憩時間</th><th>労働時間</th><th>時間外</th>
					<th class="col-sm-4">メモ</th>
				</tr>

				@foreach ($users as $index => $user)
					@php
						$work_time = $work_times->where('user_id', $user->id)->first();
					@endphp
					
					<tr class="bg-white">
						<!-- 見出し番号 -->
						<td>{{$index + 1}}</td>
						
						<!-- 名前 -->
						<td><a href="{{route('mgmt.personal', ['user_id' => $user->id])}}">{{$user->name}}</a></td>
						
						<!-- 勤務区分 -->
						@if (isset($work_time->workType->name))
							<td>{{$work_time->workType->name}}</td>
						<!-- 土日祝の場合 -->
						@elseif($date->isoFormat('ddd') === '土' || $date->isoFormat('ddd') === '日' || $holidays->isHoliday($date))
							<td></td>
						@else
							<td class="table-danger" data-toggle="tooltip" title="打刻がありません"></td>
						@endif
						
						<!-- 開始 -->
						@if (isset($work_time->start_time))
							<td>{{date('H:i', strtotime($work_time->start_time))}}</td>
						@elseif(isset($work_time))
							<!-- 出勤、遅刻、早退、遅刻/早退の場合 -->
							@if($work_time->workType->id === 1 || $work_time->workType->id === 3 ||
								$work_time->workType->id === 4 || $work_time->workType->id === 5)
								<td class="table-danger text-black-50" data-toggle="tooltip" title="出勤打刻がありません"></td>
							<!-- 欠勤、有給休暇、特別休暇の場合 -->
							@else
								<td></td>
							@endif
						<!-- 土日祝の場合 -->
						@elseif($date->isoFormat('ddd') === '土' || $date->isoFormat('ddd') === '日' || $holidays->isHoliday($date))
							<td></td>
						<!-- レコードが無い場合 -->
						@else
							<td class="table-danger" data-toggle="tooltip" title="打刻がありません"></td>
						@endif
						
						<!-- 終了 -->
						@if (isset($work_time->left_time))
							<td>{{date('H:i', strtotime($work_time->left_time))}}</td>
						@elseif(isset($work_time))
							<!-- 出勤、遅刻、早退、遅刻/早退の場合 -->
							@if($work_time->workType->id === 1 || $work_time->workType->id === 3 ||
								$work_time->workType->id === 4 || $work_time->workType->id === 5)
								<td class="table-danger text-black-50" data-toggle="tooltip" title="退勤打刻がありません"></td>
							<!-- 欠勤、有給休暇、特別休暇の場合 -->
							@else
								<td></td>
							@endif
						<!-- 土日祝の場合 -->
						@elseif($date->isoFormat('ddd') === '土' || $date->isoFormat('ddd') === '日' || $holidays->isHoliday($date))
							<td></td>
						<!-- レコードが無い場合 -->
						@else
							<td class="table-danger" data-toggle="tooltip" title="打刻がありません"></td>
						@endif
						
						<!-- 休憩時間 -->
						<td>@isset($work_time->rest_time) {{date('H:i', strtotime($work_time->rest_time))}} @endisset</td>
						
						<!-- 労働時間 -->
						<td>@isset($work_time)
								<!-- 有給休暇、特別休暇の場合 -->
								@if ($work_time->workType->id === 6 || $work_time->workType->id === 7)
									{{calcWorkTime($fixed_time->start_time, $fixed_time->left_time, $fixed_time->rest_time)}}

								<!-- 出勤、遅刻、早退、遅刻/早退の場合 -->
								@elseif (isset($work_time->start_time, $work_time->left_time))
									@php
										// 丸め誤差
										$start = $work_time->start_time;
										$left = $work_time->left_time;
										if (strtotime($fixed_time->start_time) > strtotime($start))
										{
											$start = $fixed_time->start_time;
										}
										if (strtotime($fixed_time->left_time) < strtotime($left) &&
											strtotime($work_time->rest_time) < strtotime('01:00:00'))
										{
											$left = $fixed_time->left_time;
										}
									@endphp

									<!-- 労働時間計算 -->
									{{calcWorkTime($start, $left, $work_time->rest_time)}}
								@endif
							@endisset
						</td>

						<!-- 時間外 -->
						<td>@isset($work_time->over_time) {{date('H:i', strtotime($work_time->over_time))}} @endisset</td>

						<!-- メモ -->
						<td>@isset($work_time->description) {{$work_time->description}} @endisset</td>
					</tr>
				@endforeach
			</table>
			<!------------------------------------------end 部署別勤務表 -->

			<!-- ページネーション -->
			{{$users->appends([
					'date' => $date->copy()->toDateString(),
					'department' => $dept->id,
					'disp_limit' => $disp_limit,
				])
				->links()}}
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
							<div class="col-sm-6 right">
								<div class="input-group">
									<input type="text" name="file_name"
										@if ($dept->id === 0)
											value="{{$date->format('Ym')}}_全部署_月次集計"
										@else
											value="{{$date->format('Ym')}}_{{$dept->name}}_月次集計"
										@endif
										 class="form-control text-right">
									<div class="input-group-append">
										<span class="input-group-text">.csv</span>
									</div>
								</div>
							</div>
							<button type="submit" class="col-sm-auto mr-3 btn btn-primary">出力</button>
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