@extends('layouts.app')

@section('content')
<div class="container">

    <!-- 検索フォーム -->
    <form action="" method="get">
    @csrf
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="department" class="col-form-label text-md-end">部署名：</label>
            </div>
            <div class="col-auto">
                <!-- 部署検索 JSでsubmitする -->
                <select name="department" id="department" class="form-select" required autocomplete="department" autofocus>
                    <option hidden>初期値は管理部</option>
                        <option value="">
                            部署名
                        </option>
                </select>
            </div>
        </div>
        <div class="d-flex search-column mt-3 mb-2">
            <!-- 名前＆ID検索 -->
            <input id="search-input" type="search" id="form1" class="form-control" name="keyword" placeholder="社員番号または名前検索" value="{{old('keyword')}}" />
            <button id="search-button" type="submit" class="btn btn-secondary">
            <i class="fas fa-search"></i>
            </button>
        </div>
    </form>

    <!-- 新規追加ボタン -->
    <a class="btn btn-success mb-3 employees-add" href="{{route('employees.create')}}">新規追加</a>

    <div class="d-flex">
    <!-- 表示件数絞り JSでsubmitする-->
        <form action="" method="get" class="mb-1">
        @csrf
            表示件数：
            <select id="" name="disp_limit">
                <option value="">全て</option>
                <option value="5">5件</option>
                <option value="10">10件</option>
                <option value="20">20件</option>
                <option value="50">50件</option>
                <option value="100">100件</option>
            </select>
        </form>
    </div>

    <table class="table table-bordered text-center align-middle">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">社員名</th>
            <th scope="col">社員番号</th>
            <th scope="col">部署名</th>
            <th scope="col">メールアドレス</th>
            <th scope="col">入社日</th>
        </tr>
    </thead>
    <tbody>
        @for($i=1; $i<30; $i++)
            @if($i===1)
                <tr>
                    <td>{{$i}}</td>
                    <td><a href="{{route('employees.edit')}}">佐藤大輔</a></td>
                    <td>01234567</td>
                    <td>営業部</td>
                    <td>test@test.com</td>
                    <td>2022/04/01</td>
                </tr>
            @else
                <tr>
                    <td>{{$i}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
        @endfor
    </tbody>
    </table>
</div>
@endsection