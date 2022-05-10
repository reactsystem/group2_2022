@extends('layouts.login_app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <h1 class="text-center mt-3 mb-3">勤怠管理システム</h1>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                <h2 class="h3 card-title text-center mt-2 mb-4">パスワード再設定</h2>
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <!-- token -->
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- email -->
                        <input id="email" type="hidden" name="email" value="{{ $email }}">
                        
                        <!-- email(表示させるだけ) -->
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" value="{{ $email }}" disabled>
                            </div>
                        </div>

                        <!-- パスワード設定 -->
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">新しいパスワード</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" autofocus>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- 送信ボタン -->
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
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
