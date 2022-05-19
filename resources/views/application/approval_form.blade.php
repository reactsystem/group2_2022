@extends('layouts.app')

@section('css')
<link href="{{ asset('css/application-form.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h1 class="text-center mt-3 mb-3">申請承認フォーム</h1>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="/application/mail">
                        @csrf

                        <input type="hidden" name="id" value="{{$application->id}}">

                        <!-- 申請者の名前 -->
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">申請者</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{$application->user->name}}" readonly>
                            </div>
                        </div>

                        <!-- 申請者の部署名 -->
                        <div class="row mb-3">
                            <label for="department" class="col-md-4 col-form-label text-md-end">部署名</label>
                            <div class="col-md-6">
                                <input name="department" value="{{$application->user->department->name}}" id="department" class="form-control" required readonly>
                                @error('department')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- 申請種類 -->
                        <div class="row mb-3">
                            <label for="applied_content" class="col-md-4 col-form-label text-md-end">申請内容</label>
                            <div class="col-md-6">
                                <input name="applied_content" value="{{$application->applicationType->name}}" id="applied_content" class="form-control" required autocomplete="applied_content" autofocus readonly>
                                @error('applied_content')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- 申請理由 -->
                        <div class="row mb-3">
                            <label for="reason" class="col-md-4 col-form-label text-md-end">申請理由</label>
                            <div class="col-md-6">
                                <textarea class="form-control" name="reason" id="reason" required autocomplete="reason" autofocus readonly>{{$application->reason}}</textarea>
                            </div>
                        </div>

                        <!-- 申請したい日 -->
                        <div class="row mb-3">
                            <div class="input-group date" id="datePicker" data-target-input="nearest">
                                <label for="datePicker" class="col-md-4 col-form-label text-md-end">申請日</label>
                                <div class="col-md-6">
                                    <input type="text" value="{{$application->date}}" name="date" class="form-control datetimepicker-input" data-target="#datePicker" data-toggle="datetimepicker" readonly/>
                                </div>
                            </div>
                        </div>

                        <!-- 申請したい時間 -->
                        <div class="row mb-3">

                            <!-- 開始時間 -->
                            <div class="date mb-3" id="startTimePicker" data-target-input="nearest">
                                <div class="date-group">
                                    <label for="startTimePicker" class="col-md-4 col-form-label text-md-end">開始時間</label>
                                    <div class="col-md-6">
                                        <input type="text" value="{{$application->start_time}}" name="start_time" class="form-control datetimepicker-input" data-target="#startTimePicker" data-toggle="datetimepicker" readonly/>
                                    </div>
                                </div>
                            </div>

                            <!-- 終了時間 -->
                            <div class="date" id="endTimePicker" data-target-input="nearest">
                                <div class="date-group">
                                    <label for="endTimePicker" class="col-md-4 col-form-label text-md-end">終了時間</label>
                                    <div class="col-md-6">
                                        <input type="text" value="{{$application->end_time}}" name="end_time" class="form-control datetimepicker-input" data-target="#endTimePicker" data-toggle="datetimepicker" readonly/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 承認/差し戻しコメント -->
                        <div class="row mb-3">
                            <label for="comment" class="col-md-4 col-form-label text-md-end">コメント</label>
                            <div class="col-md-6">
                                <textarea class="form-control" name="comment" id="comment" autocomplete="comment" autofocus>{{ old('comment') }}</textarea>
                                <p class="help-block">※60文字以内で書いてください</p>
                                @if ($errors->has('comment'))
                                <div class="alert alert-danger" role="alert">
                                    {{ $errors->first('comment') }}
                                </div>
                                @endif
                            </div>
                        </div>


                        <!-- 承認/差し戻しボタン -->
                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" name="result" value="承認" class="btn btn-primary ml-2 mr-4">
                                    承認する
                                </button>
                                <button type="submit" name="result" value="差し戻し" class="btn btn-danger ml-4 mr-3">
                                    差し戻す
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection