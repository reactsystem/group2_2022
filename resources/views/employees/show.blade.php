@extends('layouts.app')

@section('css')
<link href="{{ asset('css/employees.css') }}" rel="stylesheet">
@endsection

@section('content')
@if (session('message'))
    <div class="alert alert-success text-center" role="alert">
        {{ session('message') }}
    </div>
@endif
<div class="container">

    <!-- 検索フォーム -->
    <form action="" method="get" id="search">
        <div class="row g-3 align-items-center">
            <div class="search-box mb-3">
                <div class="flex-grow-0">
                    <label for="department" class="col-form-label text-md-end">部署名：</label>
                </div>
                <div class="flex-grow-2 mr-3">
                    <select name="department" id="department" class="form-select">
                        @foreach($departments as $department)
                            @if($loop->first)
                                <option value="0" @if(\Request::get('department') === '0') selected @endif>全て</option>
                            @endif
                                <option value="{{$department->id}}" @if(\Request::get('department') == $department->id) selected @endif>
                                    {{$department->name}}
                                </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex search-column flex-grow-2">
                    <!-- 名前＆ID検索 -->
                    <input id="search-input" type="search" class="form-control" name="keyword" placeholder="社員番号または名前検索" @if(isset( $input_keyword )) value="{{ $input_keyword }}" @endif/>
                    <button type="submit" id="search-button" class="btn btn-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    <div class="d-flex mt-3 mb-2">
        表示件数：
        <select id="limit" name="disp_limit">
            @foreach($limit_disp as $limit)
                <option id="limit_disp" value="{{$loop->index}}" @if(\Request::get('disp_limit') == $loop->index) selected @endif>{{$limit}}</option>
            @endforeach
        </select>   
    </form>
        @if(!empty($users->appends(request()->input())->links()))
        <span class="links ml-5">
            {{$users->appends(request()->input())->links('vendor.pagination.bootstrap-5')}}
        </span>
        <span class="ml-2 mt-2">{{ $users->total() }}件中{{ $users->firstItem() }}〜{{ $users->lastItem() }} 件を表示</span>
        @endif
        <!-- 新規追加ボタン -->
        <a class="btn btn-success mb-3 employees-add ml-auto" href="{{route('employees.add')}}">新規追加</a>
    </div>

    <table id="employees" class="table table-bordered text-center align-middle">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">社員名</th>
            <th scope="col">@sortablelink('id', '社員番号')</th>
            <th scope="col">部署名</th>
            <th scope="col">メールアドレス</th>
            <th scope="col">@sortablelink('joining', '入社日')</th>
            <th scope="col">@sortablelink('manager', '管理者権限')</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td><a href="{{route('employees.edit', ['user' => $user->id])}}">{{$user->name}}</a></td>
            <td>{{$user->id}}</td>
            <td>{{$user->department->name}}</td>
            <td>{{$user->email}}</td>
            <td>{{str_replace('-', '/', $user->joining)}}</td>
            <td>
                @if($user->manager === 1)
                    <i class="fa-solid fa-check text-success"><p class="d-none">{{$user->manager}}</p></i>
                @else
                    <p class="d-none">{{$user->manager}}</p>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
    </table>
</div>
@endsection

@section('js')
<script src="{{asset('js/list.js')}}"></script>
@endsection