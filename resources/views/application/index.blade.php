@extends('layouts.app')

@section('css')
<link href="{{ asset('css/employees.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">

    <!-- 検索フォーム -->
<<<<<<< HEAD
    <form action="" method="get">
    @csrf
=======
    <form id="search" action="" method="get">
>>>>>>> b818255b127561bb3185c9060a56de41fc2a0e0d
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="department" class="col-form-label text-md-end">部署名：</label>
            </div>
            <div class="col-auto">
<<<<<<< HEAD
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

=======
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
    
>>>>>>> b818255b127561bb3185c9060a56de41fc2a0e0d
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
<<<<<<< HEAD
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
=======
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
>>>>>>> b818255b127561bb3185c9060a56de41fc2a0e0d
@endsection