@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h1 class="text-center mt-3 mb-3">勤怠管理システム</h1>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h2 class="h3 card-title text-center mt-2 mb-4">社員情報修正</h2>
                    <form method="POST" action="{{ route('employees.update', ['user' => $editUser->id]) }}">
                        @csrf

                        <!-- 名前 -->
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name') ?? $editUser->name}}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- 部署名 -->
                        <div class="row mb-3">
                            <label for="department" class="col-md-4 col-form-label text-md-end">部署名</label>
                            <div class="col-md-6">
                                <select name="department" id="department" class="form-select" required autocomplete="department" autofocus>
                                <option value="{{$editUser->department->id}}" selected>{{$editUser->department->name}}</option>
                                    @foreach($departments as $department)
                                        <option value="{{$department->id}}" @if(old('department') == $department->id) selected @endif>
                                            {{$department->name}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- メールアドレス -->
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email') ?? $editUser->email}}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- 退社日 -->
                        <div class="row mb-3">
                            <div class="input-group date" id="datePicker" data-target-input="nearest">
                                <label for="datePicker" class="col-md-4 col-form-label text-md-end">退社日</label>
                                <div class="col-md-6">
                                    <input type="text" name="leaving" value="{{old('leaving')}}" data-name="{{old('leaving')}}" class="form-control datetimepicker-input @error('leaving') is-invalid @enderror" data-target="#datePicker" data-toggle="datetimepicker"/>
                                    @error('leaving')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="input-group-append" data-target="#datePicker" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div> 
                            </div>
                        </div>

                        <!-- 管理者権限 -->
                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input @error('authority') is-invalid @enderror" id="authority" type="checkbox" name="authority" value="1" data-checked="{{ old('authority', $editUser->manager) }}">
                                    <label class="form-check-label" for="authority">
                                        管理者権限を持たせる
                                    </label>
                                    @error('authority')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- 編集ボタン -->
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    編集する
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

@section('js')
<script src="{{asset('js/employeeEdit.js')}}"></script>
@endsection
