@extends('layouts.app')

@section('css')
<link href="{{ asset('css/employees.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    @if (session()->has('application'))
    <div class="alert alert-primary" role="alert">
        {{session('application')}}
    </div>
    @endif

    <!-- 検索フォーム -->
    <form id="search" action="" method="get">
        <div class="d-flex mt-3 mb-2">
            表示件数：
            <select id="limit" name="disp_limit">
                @foreach($limit_disp as $limit)
                    <option id="limit_disp" value="{{$loop->index}}" @if(\Request::get('disp_limit') == $loop->index) selected @endif>{{$limit}}</option>
                @endforeach
            </select>   
    </form>
            @if(!empty($applications->appends(request()->input())->links()))
            <span class="links ml-5">
                {{$applications->appends(request()->input())->links('vendor.pagination.bootstrap-5')}}
            </span>
            <span class="ml-2 mt-2">{{ $applications->total() }}件中{{ $applications->firstItem() }}〜{{ $applications->lastItem() }} 件を表示</span>
            @endif
            <!-- 新規申請ボタン -->
            <a class="btn btn-primary mb-3 ml-3" href="{{ route('application.indexSelf', ['status'=>'all']) }}">全ての申請を表示</a>
            <!-- 新規申請ボタン -->
            <a class="btn btn-success mb-3 employees-add ml-auto" href="{{ route('application.show', array_merge(Request::query(), ['date' => ''])) }}">新規申請</a>
        </div>
    
    <table id="application" class="table table-bordered text-center align-middle">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">対象日</th>
            <th scope="col">申請内容</th>
            <th scope="col">申請理由</th>
            <th scope="col">ステータス</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($applications as $application)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$application->date}}</td>
                <td>{{$application->applicationType->name}}</td>
                <td>{{Str::limit($application->reason, 40)}}</td>
                <td>
                    @if ($application->status == 0)
                    申請中
                    @elseif ($application->status == 1)
                    承認済み
                    @elseif ($application->status == 2)
                    却下済み
                    @elseif ($application->status == 3)
                    取り下げ済み
                    @endif
                </td>
                <td>
                    <button	type="button" class="btn btn-primary btn-sm"
						data-toggle="modal" data-target="#modal-check"
						data-id="{{$application->id}}"
						data-name="{{$application->user->name}}"
						data-dept="{{$application->user->department->name}}"
						data-dept_id="{{$application->user->department->id}}"
						data-type="{{$application->applicationType->name}}"
						data-reason="{{$application->reason}}"
						data-date="{{date('Y/m/d', strtotime($application->date))}}" 
						data-start="{{isset($application->start_time) ? date('H:i', strtotime($application->start_time)):''}}"
						data-end="{{isset($application->end_time) ? date('H:i', strtotime($application->end_time)):''}}">
                    確認</button>
                    <button	type="button" class="btn btn-secondary btn-sm"
                        data-toggle="modal" data-target="#modal-edit"
                        data-id="{{$application->id}}"
                        data-name="{{$application->user->name}}"
                        data-dept="{{$application->user->department->name}}"
                        data-dept_id="{{$application->user->department->id}}"
                        data-type="{{$application->application_type_id}}"
                        data-reason="{{$application->reason}}"
                        data-date="{{date('Y/m/d', strtotime($application->date))}}" 
                        data-start="{{isset($application->start_time) ? date('H:i', strtotime($application->start_time)):''}}"
                        data-end="{{isset($application->end_time) ? date('H:i', strtotime($application->end_time)):''}}">
                    編集</button>
					<button	type="button" class="btn btn-success btn-sm"
                        data-toggle="modal" data-target="#modal-reuse"
                        data-id="{{$application->id}}"
                        data-name="{{$application->user->name}}"
                        data-dept="{{$application->user->department->name}}"
                        data-dept_id="{{$application->user->department->id}}"
                        data-type="{{$application->application_type_id}}"
                        data-reason="{{$application->reason}}"
                        data-date="{{date('Y/m/d', strtotime($application->date))}}" 
                        data-start="{{isset($application->start_time) ? date('H:i', strtotime($application->start_time)):''}}"
                        data-end="{{isset($application->end_time) ? date('H:i', strtotime($application->end_time)):''}}">
                    再利用</button>
                </td>
            </tr>
        @endforeach
    </tbody>
    </table>
</div>
<!-- Modal ---------------------------------------------------->
<!-- 申請確認(取り下げ)フォーム -->
<div class="modal fade" id="modal-check" tabindex="-1" aria-labelledby="label-fixed" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<form action="index/stop" method="POST">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title">申請確認フォーム</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<input type="hidden" name="id" id="app-id">
					<input type="hidden" name="department_id" id="app-dept-id">

					<!-- 申請者の名前 -->
					<div class="row mb-3">
						<label for="name" class="col-md-4 col-form-label text-md-end">申請者</label>
						<div class="col-md-6">
							<input type="text" name="name" id="app-name" class="form-control" readonly>
						</div>
					</div>

					<!-- 申請者の部署名 -->
					<div class="row mb-3">
						<label for="department" class="col-md-4 col-form-label text-md-end">部署名</label>
						<div class="col-md-6">
							<input type="text" name="department" id="app-dept" class="form-control" readonly>
						</div>
					</div>

					<!-- 申請種類 -->
					<div class="row mb-3">
						<label for="applied_content" class="col-md-4 col-form-label text-md-end">申請内容</label>
						<div class="col-md-6">
							<input type="text" name="applied_content" id="app-type" class="form-control" readonly>
						</div>
					</div>

					<!-- 申請理由 -->
					<div class="row mb-3">
						<label for="reason" class="col-md-4 col-form-label text-md-end">申請理由</label>
						<div class="col-md-6">
							<textarea name="reason" id="app-reason" class="form-control" readonly></textarea>
						</div>
					</div>

					<!-- 申請したい日 -->
					<div class="row mb-3">
						<label for="date" class="col-md-4 col-form-label text-md-end">申請日</label>
						<div class="col-md-6">
							<input type="text" name="date" id="app-date" class="form-control" readonly>
						</div>
					</div>

					<!-- 開始時間 -->
					<div class="row mb-3">
						<label for="start_time" class="col-md-4 col-form-label text-md-end">開始時間</label>
						<div class="col-md-6">
							<input type="text" name="start_time" id="app-start" class="form-control" readonly>
						</div>
					</div>

					<!-- 終了時間 -->
					<div class="row mb-3">
						<label for="end_time" class="col-md-4 col-form-label text-md-end">終了時間</label>
						<div class="col-md-6">
							<input type="text" name="end_time" id="app-end" class="form-control" readonly>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<!-- 取り下げボタン -->
					<div class="col-md-8 offset-md-4">
						<button type="submit" name="stop" value="3" class="btn btn-secondary" id="btn-stop">
							取り下げ
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-------------------------------------------------end Modal -->

<!-- Modal ---------------------------------------------------->
<!-- 申請編集フォーム -->
<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="label-fixed" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<form action="index/edit" method="POST">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title">申請編集フォーム</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<input type="hidden" name="id" id="app-id-edit">
					<input type="hidden" name="department_id" id="app-dept-id-edit">

					<!-- 申請者の名前 -->
					<div class="row mb-3">
						<label for="name" class="col-md-4 col-form-label text-md-end">申請者</label>
						<div class="col-md-6">
							<input type="text" name="name" id="app-name-edit" class="form-control" readonly>
						</div>
					</div>

					<!-- 申請者の部署名 -->
					<div class="row mb-3">
						<label for="department" class="col-md-4 col-form-label text-md-end">部署名</label>
						<div class="col-md-6">
							<input type="text" name="department" id="app-dept-edit" class="form-control" readonly>
						</div>
					</div>

					<!-- 申請種類 -->
					<div class="row mb-3">
						<label for="applied_content" class="col-md-4 col-form-label text-md-end">申請内容</label>
						<div class="col-md-6">
                            <select name="appliedContent" id="app-type-edit" class="form-select" required>
                                @foreach($types as $type)
                                    <option value="{{$type->id}}">{{$type->name}}</option>
                                @endforeach
                            </select>
                            @error('appliedContent')
                                <span class="invalid-feedback" role="alert">
                                    <strong id="appTypeError">{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="appTypeError d-none" role="alert">
                                <strong class="appTypeErrorMsg text-danger"></strong>
                            </div>
						</div>
					</div>

					<!-- 申請理由 -->
					<div class="row mb-3">
						<label for="reason" class="col-md-4 col-form-label text-md-end">申請理由</label>
						<div class="col-md-6">
							<textarea name="reason" id="app-reason-edit" class="form-control"></textarea>
						</div>
					</div>

					<!-- 申請したい日 -->
					<div class="row mb-3">
						<label for="date" class="col-md-4 col-form-label text-md-end">申請日</label>
						<div class="col-md-6">
							<div id="datePicker" class="input-group date date-width" data-target-input="nearest">
								<input type="text" id="app-date-edit" name="date" value="{{old('date')}}" data-name="{{old('date')}}" class="form-control datetimepicker-input" data-target="#datePicker" data-toggle="datetimepicker"/>
								<div class="input-group-append" data-target="#datePicker" data-toggle="datetimepicker">
									<div class="input-group-text"><i class="fa fa-calendar"></i></div>
								</div>
								@error('date')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
					</div>

					<!-- 開始時間 -->
					<div class="row mb-3">
						<label for="start_time" class="col-md-4 col-form-label text-md-end">開始時間</label>
						<div class="col-md-6">
							<div id="startTimePicker" class="input-group date date-width" data-target-input="nearest">                                
								<input type="text" id="app-start-edit" name="start_time" value="{{old('start_time')}}" data-name="{{old('start_time')}}" class="form-control datetimepicker-input @error('start_time') is-invalid @enderror" data-target="#startTimePicker" data-toggle="datetimepicker"/>                              
								<div class="input-group-append date-text" data-target="#startTimePicker" data-toggle="datetimepicker">
									<div class="input-group-text"><i class="fa fa-clock"></i></div>
								</div>
								@error('start_time')
									<span class="invalid-feedback" role="alert">
										<strong id="startTimeError">{{ $message }}</strong>
									</span>
								@enderror
								<div class="startTimeError d-none" role="alert">
									<strong class="startTimeErrorMsg text-danger"></strong>
								</div>
							</div>
						</div>
					</div>

					<!-- 終了時間 -->
					<div class="row mb-3">
						<label for="end_time" class="col-md-4 col-form-label text-md-end">終了時間</label>
						<div class="col-md-6">
							<div id="endTimePicker" class="input-group date date-width" data-target-input="nearest">
								<input type="text"  id="app-end-edit" name="end_time" value="{{old('end_time')}}" data-name="{{old('end_time')}}" class="form-control datetimepicker-input @error('end_time') is-invalid @enderror" data-target="#endTimePicker" data-toggle="datetimepicker"/>
								<div class="input-group-append date-text" data-target="#endTimePicker" data-toggle="datetimepicker">
									<div class="input-group-text"><i class="fa fa-clock"></i></div>
								</div>
								@error('end_time')
									<span class="invalid-feedback" role="alert">
										<strong id="endTimeError">{{ $message }}</strong>
									</span>
								@enderror
								<div class="endTimeError d-none" role="alert">
									<strong class="endTimeErrorMsg text-danger"></strong>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<!-- 更新ボタン -->
					<div class="col-md-8 offset-md-4">
						<button type="submit" name="update" value="#" class="btn btn-secondary" id="btn-update">
							更新
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-------------------------------------------------end Modal -->

<!-- Modal ---------------------------------------------------->
<!-- 申請再利用フォーム -->
<div class="modal fade" id="modal-reuse" tabindex="-1" aria-labelledby="label-fixed" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<form action="index/reuse" method="POST">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title">申請再利用フォーム</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<input type="hidden" name="id" id="app-id-reuse">
					<input type="hidden" name="department_id" id="app-dept-id-reuse">

					<!-- 申請者の名前 -->
					<div class="row mb-3">
						<label for="name" class="col-md-4 col-form-label text-md-end">申請者</label>
						<div class="col-md-6">
							<input type="text" name="name" id="app-name-reuse" class="form-control" readonly>
						</div>
					</div>

					<!-- 申請者の部署名 -->
					<div class="row mb-3">
						<label for="department" class="col-md-4 col-form-label text-md-end">部署名</label>
						<div class="col-md-6">
							<input type="text" name="department" id="app-dept-reuse" class="form-control" readonly>
						</div>
					</div>

					<!-- 申請種類 -->
					<div class="row mb-3">
						<label for="applied_content" class="col-md-4 col-form-label text-md-end">申請内容</label>
						<div class="col-md-6">
                            <select name="appliedContent" id="app-type-reuse" class="form-select" required>
                                @foreach($types as $type)
                                    <option value="{{$type->id}}">{{$type->name}}</option>
                                @endforeach
                            </select>
                            @error('appliedContent')
                                <span class="invalid-feedback" role="alert">
                                    <strong id="appTypeError">{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="appTypeError d-none" role="alert">
                                <strong class="appTypeErrorMsg text-danger"></strong>
                            </div>
						</div>
					</div>

					<!-- 申請理由 -->
					<div class="row mb-3">
						<label for="reason" class="col-md-4 col-form-label text-md-end">申請理由</label>
						<div class="col-md-6">
							<textarea name="reason" id="app-reason-reuse" class="form-control"></textarea>
						</div>
					</div>

					<!-- 申請したい日 -->
					<div class="row mb-3">
						<label for="date" class="col-md-4 col-form-label text-md-end">申請日</label>
						<div class="col-md-6">
							<div id="datePicker" class="input-group date date-width" data-target-input="nearest">
								<input type="text" id="app-date-reuse" name="date" value="{{old('date')}}" data-name="{{old('date')}}" class="form-control datetimepicker-input" data-target="#datePicker" data-toggle="datetimepicker"/>
								<div class="input-group-append" data-target="#datePicker" data-toggle="datetimepicker">
									<div class="input-group-text"><i class="fa fa-calendar"></i></div>
								</div>
								@error('date')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
					</div>

					<!-- 開始時間 -->
					<div class="row mb-3">
						<label for="start_time" class="col-md-4 col-form-label text-md-end">開始時間</label>
						<div class="col-md-6">
							<div id="startTimePicker" class="input-group date date-width" data-target-input="nearest">                                
								<input type="text" id="app-start-reuse" name="start_time" value="{{old('start_time')}}" data-name="{{old('start_time')}}" class="form-control datetimepicker-input @error('start_time') is-invalid @enderror" data-target="#startTimePicker" data-toggle="datetimepicker"/>                              
								<div class="input-group-append date-text" data-target="#startTimePicker" data-toggle="datetimepicker">
									<div class="input-group-text"><i class="fa fa-clock"></i></div>
								</div>
								@error('start_time')
									<span class="invalid-feedback" role="alert">
										<strong id="startTimeError">{{ $message }}</strong>
									</span>
								@enderror
								<div class="startTimeError d-none" role="alert">
									<strong class="startTimeErrorMsg text-danger"></strong>
								</div>
							</div>
						</div>
					</div>

					<!-- 終了時間 -->
					<div class="row mb-3">
						<label for="end_time" class="col-md-4 col-form-label text-md-end">終了時間</label>
						<div class="col-md-6">
							<div id="endTimePicker" class="input-group date date-width" data-target-input="nearest">
								<input type="text"  id="app-end-reuse" name="end_time" value="{{old('end_time')}}" data-name="{{old('end_time')}}" class="form-control datetimepicker-input @error('end_time') is-invalid @enderror" data-target="#endTimePicker" data-toggle="datetimepicker"/>
								<div class="input-group-append date-text" data-target="#endTimePicker" data-toggle="datetimepicker">
									<div class="input-group-text"><i class="fa fa-clock"></i></div>
								</div>
								@error('end_time')
									<span class="invalid-feedback" role="alert">
										<strong id="endTimeError">{{ $message }}</strong>
									</span>
								@enderror
								<div class="endTimeError d-none" role="alert">
									<strong class="endTimeErrorMsg text-danger"></strong>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<!-- 再利用ボタン -->
					<div class="col-md-8 offset-md-4">
						<button type="submit" name="reuse" value="#" class="btn btn-secondary" id="btn-reuse">
							再利用して新規申請
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-------------------------------------------------end Modal -->


@endsection

@section('js')
<script src="{{asset('js/show_self_application.js')}}"></script>
@endsection