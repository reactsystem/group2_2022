@extends('layouts.app')

@section('css')
<link href="{{ asset('css/employees.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">

    <!-- 検索フォーム -->
    <form id="search" action="" method="get">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="department" class="col-form-label text-md-end">部署名：</label>
            </div>
            <div class="col-auto">
                <select name="department" id="department" class="form-select">
                    <option value="{{$loginUser}}" selected>{{$loginUserDepartment}}</option>
                    @foreach($departments as $department)
                        @if($loginUser === $department->id)
                            @continue
                        @else
                            @if($loop->first)
                                <option value="0" @if(\Request::get('department') === '0') selected @endif>全て</option>
                            @endif
                            <option value="{{$department->id}}" @if(\Request::get('department') == $department->id) selected @endif>
                                {{$department->name}}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="d-flex mt-3 mb-2">
            表示件数：
            <select id="limit" name="disp_limit">
                @foreach($limit_disp as $limit)
                    <option value="{{$loop->index}}" @if(\Request::get('disp_limit') == $loop->index) selected @endif>{{$limit}}</option>
                @endforeach
            </select>   
    </form>
            @if(!empty($applications->appends(request()->input())->links()))
            <span class="links ml-5">
                {{$applications->appends(request()->input())->links('vendor.pagination.bootstrap-5')}}
            </span>
            <span class="ml-2 mt-2">{{ $applications->total() }}件中{{ $applications->firstItem() }}〜{{ $applications->lastItem() }} 件を表示</span>
            @endif
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
        @foreach($applications as $application)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td><a href="{{route('application.approve', ['user' => $application->user->id, 'application' => $application->id])}}">{{$application->user->name}}</a></td>
                <td>{{$application->user->department->name}}</td>
                <td>{{$application->date}}</td>
                <td>{{$application->applicationType->name}}</td>
            </tr>
        @endforeach
    </tbody>
    </table>
</div>
@endsection

@section('js')
    <script>
        const searchForm = document.getElementById('search')
        const department = document.getElementById('department');
        const limit = document.getElementById('limit');
        department.addEventListener('change', (e)=>{
            searchForm.submit();
        })
        limit.addEventListener('change', (e)=>{
            searchForm.submit();
        })
    </script>
@endsection