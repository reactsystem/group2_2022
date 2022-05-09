@extends('layouts.app')

@section('css')
<link href="{{ asset('css/employees.css') }}" rel="stylesheet">
@endsection

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
                    <option hidden>初期値はログインユーザーの所属部署</option>
                        <option value="">
                            部署名
                        </option>
                </select>
            </div>
        </div>
    </form>

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
            <th scope="col">申請者</th>
            <th scope="col">部署名</th>
            <th scope="col">対象日</th>
            <th scope="col">申請内容</th>
        </tr>
    </thead>
    <tbody>
        @for($i=1; $i<30; $i++)
            @if($i===1)
                <tr>
                    <td>{{$i}}</td>
                    <td><a href="#">佐藤大輔</a></td>
                    <td>営業部</td>
                    <td>2022/05/06</td>
                    <td>有給休暇</td>
                </tr>
            @else
                <tr>
                    <td>{{$i}}</td>
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