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
                    <form method="POST" action="">
                        @csrf

                        <!-- 申請者の名前 -->
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">申請者</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="申請者の名前" disabled>
                            </div>
                        </div>

                        <!-- 申請者の部署名 -->
                        <div class="row mb-3">
                            <label for="application-department" class="col-md-4 col-form-label text-md-end">部署名</label>
                            <div class="col-md-6">
                                <select name="department" id="application-department" class="form-select" required disabled>
                                        <option value="1">有給</option>
                                        <option value="2">残業</option>
                                        <option value="3">休暇</option>
                                </select>
                                @error('department')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- 申請種類 -->
                        <div class="row mb-3">
                            <label for="applied-content" class="col-md-4 col-form-label text-md-end">申請内容</label>
                            <div class="col-md-6">
                                <select name="applied-content" id="applied-content" class="form-select" required autocomplete="applied-content" autofocus>
                                    <option hidden>選択してください</option>
                                        <option value="1">有給</option>
                                        <option value="2">残業</option>
                                        <option value="3">休暇</option>
                                </select>
                                @error('applied-content')
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
                                <textarea class="form-control" name="reason" id="reason" required autocomplete="reason" autofocus></textarea>
                            </div>
                        </div>

                        <!-- 申請したい日 -->
                        <div class="row mb-3">
                            <div class="input-group date" id="datePicker" data-target-input="nearest">
                                <label for="datePicker" class="col-md-4 col-form-label text-md-end">申請日</label>
                                <div class="col-md-6">
                                    <input type="text" value="" class="form-control datetimepicker-input" data-target="#datePicker" data-toggle="datetimepicker"/>
                                </div>
                                <div class="input-group-append" data-target="#datePicker" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
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
                                        <input type="text" name="start_time" class="form-control datetimepicker-input application-time" data-target="#startTimePicker" data-toggle="datetimepicker"/>
                                    </div>
                                    <div class="input-group-text date-text application-time" data-target="#startTimePicker" data-toggle="datetimepicker">
                                        <span><i class="fa fa-clock"></i></span>
                                    </div>       
                                </div>
                            </div>

                            <!-- 終了時間 -->
                            <div class="date" id="endTimePicker" data-target-input="nearest">
                                <div class="date-group">
                                    <label for="endTimePicker" class="col-md-4 col-form-label text-md-end">終了時間</label>
                                    <div class="col-md-6">
                                        <input type="text" name="end_time" class="form-control datetimepicker-input application-time" data-target="#endTimePicker" data-toggle="datetimepicker"/>
                                    </div>
                                    <div class="input-group-text date-text application-time" data-target="#endTimePicker" data-toggle="datetimepicker">
                                        <span><i class="fa fa-clock"></i></span>
                                    </div>           
                                </div>
                            </div>

                        </div>

                        <!-- 申請ボタン -->
                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
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
@endsection

