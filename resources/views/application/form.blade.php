@extends('layouts.app')

@section('css')
<link href="{{ asset('css/application-form.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h1 class="text-center mt-3 mb-3">申請フォーム</h1>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{route('application.create', ['user' => $user->id])}}" id="form-app">
                        @csrf
						<input type="hidden" id="left-days" value="{{$left_days}}">

                        <!-- 申請者の名前 -->
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">申請者</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{$user->name}}" disabled>
                            </div>
                        </div>

                        <!-- 申請者の部署名 -->
                        <div class="row mb-3">
                            <label for="department" class="col-md-4 col-form-label text-md-end">部署名</label>
                            <div class="col-md-6">
                                <input type="text" id="department" class="form-control" name="department" value="{{$user->department->name}}" disabled>
                            </div>
                        </div>

                        <!-- 申請種類 -->
                        <div class="row mb-3">
                            <label for="applied-content" class="col-md-4 col-form-label text-md-end required">申請内容<span class="badge badge-danger ml-1">必須</span></label>
                            <div class="col-md-6">
                                <select name="appliedContent" id="applied-content" class="form-select @error('appliedContent') is-invalid @enderror" required autocomplete="applied-content" autofocus>
                                    <option hidden value="">選択してください</option>
                                    @foreach($types as $type)
                                        <option value="{{$type->id}}" @if(old('appliedContent') == $type->id) selected @endif>
                                            {{$type->name}}
                                        </option>
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
                                <textarea class="form-control @error('reason') is-invalid @enderror" name="reason" id="reason" autocomplete="reason" autofocus>{{old('reason')}}</textarea>
                                <p class="help-block">※60文字以内で書いてください</p>
                                @error('reason')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- 申請したい日 -->
                        <div class="row mb-3 datetime">
                            <label for="datePicker" class="col-md-4 col-form-label text-md-end required">申請日<span class="badge badge-danger ml-1">必須</span></label>
                            <div class="col-md-12">
								<div id="datePicker" class="input-group date date-width" data-target-input="nearest">
									<input type="text" id="datePicker" name="date" value="{{old('date')}}" data-name="{{old('date')}}" class="form-control datetimepicker-input @error('date') is-invalid @enderror" data-target="#datePicker" data-toggle="datetimepicker"/>
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

                        <!-- 申請したい時間 -->

						<!-- 開始時間 -->
						<div class="row mb-4 mt-2 datetime">
							<label for="startTimePicker" class="col-md-4 col-form-label text-md-end">開始時間<span class="startTimePicker"></span></label>
							<div class="col-md-12">
								<div id="startTimePicker" class="input-group date date-width" data-target-input="nearest">
									<input type="text" id="startTimePicker" name="start_time" value="{{old('start_time')}}" data-name="{{old('start_time')}}" class="form-control datetimepicker-input @error('start_time') is-invalid @enderror" data-target="#startTimePicker" data-toggle="datetimepicker"/>
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
						<div class="row mb-5 mt-2 datetime">
							<label for="endTimePicker" class="col-md-4 col-form-label text-md-end">終了時間<span class="endTimePicker"></span></label>
							<div class="col-md-12">
								<div id="endTimePicker" class="input-group date date-width" data-target-input="nearest">
									<input type="text" name="end_time" value="{{old('end_time')}}" data-name="{{old('end_time')}}" class="form-control datetimepicker-input @error('end_time') is-invalid @enderror" data-target="#endTimePicker" data-toggle="datetimepicker"/>
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

                        <!-- 申請ボタン -->
                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button id="application-button" type="submit" class="btn btn-primary">
                                    申請する
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<span id="left_time" class="d-none" data-name="{{$left_time}}"></span>
@endsection

@section('js')
<script src="{{asset('js/appDateTime.js')}}"></script>
@endsection