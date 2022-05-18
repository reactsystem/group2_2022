@extends('layouts.app')

@section('css')
<link href="{{ asset('css/input.css') }}" rel="stylesheet">
@endsection

    <header>
    </header>
    @section('content')

        <div class="container">
            <div class="contents">

                <div class="select_month col">
                    <p class="mr-4">{{$user->name}}さんの勤務表</p>
                    <form action="" method="POST">
                        <select name="month" class="form-select col" aria-label="Default select example">
                            <option value="">２０２２年５月</option>
                        </select>
                    </form>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                          <li class="page-item"><a class="page-link" href="#">＜前月へ</a></li>
                          <li class="page-item"><a class="page-link" href="#">翌月へ＞</a></li>
                        </ul>
                      </nav>
                </div>

                <table class="table col-7 table-sm">
                    <thead>
                    <tr class="table-success">
                        <th colspan="4" scope="col">
                            <button class="btn" type="button" data-toggle="collapse" data-target=".collapse0" aria-expanded="false" style="width: 100%; text-align: left;">
                                勤務時間項目
                            </button>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><div class="collapse collapse0">所定時間</td>
                        <td><div class="collapse collapse0">休憩時間</td>
                        <td><div class="collapse collapse0">労働時間</td>
                        <td><div class="collapse collapse0">時間外</td>
                    </tr>
                    <tr>
                        <td><div class="collapse collapse0">{{$fixedWorkTime}}</td>
                        <td><div class="collapse collapse0">{{$totalRestTime}}</td>
                        <td><div class="collapse collapse0">{{$totalWorkTime}}</td>
                        <td><div class="collapse collapse0">{{$totalOverTime}}</td>
                    </tr>
                    </tbody>
                </table>

                <table class="table col-7 table-sm">
                    <thead>
                        <tr class="table-success">
                            <th colspan="4" scope="col">
                                <button class="btn" type="button" data-toggle="collapse" data-target=".collapse1" aria-expanded="false" style="width: 100%; text-align: left;">
                                    勤務区分項目
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><div class="collapse collapse1">出勤</td>
                        <td><div class="collapse collapse1">欠勤</td>
                        <td><div class="collapse collapse1">遅刻</td>
                        <td><div class="collapse collapse1">早退</td>
                    </tr>
                    <tr>
                        <td><div class="collapse collapse1">1</td>
                        <td><div class="collapse collapse1">0</td>
                        <td><div class="collapse collapse1">0</td>
                        <td><div class="collapse collapse1">0</td>
                    </tr>
                    </tbody>
                </table>

                <table class="table col-7 table-sm">
                    <thead>
                        <tr class="table-success">
                            <th colspan="4" scope="col">
                                <button class="btn" type="button" data-toggle="collapse" data-target=".collapse2" aria-expanded="false" style="width: 100%; text-align: left;">
                                    休暇項目
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><div class="collapse collapse2">有給休暇取得日数</td>
                        <td><div class="collapse collapse2">特別休暇取得日数</td>
                        <td><div class="collapse collapse2">有給休暇残り日数</td>
                        <td><div class="collapse collapse2"></td>
                    </tr>
                    <tr>
                        <td><div class="collapse collapse2">{{$total_leave}}</td>
                        <td><div class="collapse collapse2">0</td>
                        <td><div class="collapse collapse2">{{$remain_leave}}</td>
                        <td><div class="collapse collapse2"></td>
                    </tr>
                    </tbody>
                </table>

                <div class="input_form">
                <form action="" method="POST">
                    @csrf
                    <button type="button" class="btn btn-primary btn-lg mb-3">更新する</button>

                        <table class="table table-bordered col-10 table-sm text-center">
                            <thead>
                            <tr class="table-info">
                                <th scope="col">日付</th>
                                <th scope="col">勤務区分</th>
                                <th scope="col">開始</th>
                                <th scope="col">終了</th>
                                <th scope="col">休憩時間</th>
                                <th scope="col">労働時間</th>
                                <th scope="col">時間外</th>
                                <th scope="col">メモ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($period as $day)
                            <tr>
                                <td>{{$day->format('m/d')}}({{$day->isoFormat(('ddd'))}})</td>
                                <td><select name="work_type">
                                    <option value="" @if(empty($userInfo[$loop->index])) selected @endif></option>
                                    @foreach($workTypes as $workType)
                                        <option value="{{$workType->id}}" @if(!empty($userInfo[$loop->index]) && $userInfo[$loop->index]->work_type_id == $workType->id) selected @endif>{{$workType->name}}</option>
                                    @endforeach
                                    </select>
                                </td>
                                <td>
                                    @if(!empty($userInfo[$loop->index]->start_time))
                                        <input type="text" size="6" name="start_time" value="{{substr($userInfo[$loop->index]->start_time, 0, 5)}}">
                                    @else
                                        <input type="text" size="6" name="start_time" value="">
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($userInfo[$loop->index]->left_time))
                                        <input type="text" size="6" name="left_time" value="{{substr($userInfo[$loop->index]->left_time, 0, 5)}}">
                                    @else
                                        <input type="text" size="6" name="left_time" value="">
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($userInfo[$loop->index]->rest_time))
                                        {{substr($userInfo[$loop->index]->rest_time, 0, 5)}}
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($userInfo[$loop->index]->over_time))
                                        {{substr($userInfo[$loop->index]->over_time, 0, 5)}}
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($userInfo[$loop->index]->over_time))
                                        {{substr($userInfo[$loop->index]->over_time, 0, 5)}}
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($userInfo[$loop->index]->description))
                                        {{$userInfo[$loop->index]->description}}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            </tbody>

                            <tfoot>
                            <tr class="table-info">
                                <td colspan="3">合計</td>
                                <td>所定時間</td>
                                <td>休憩時間</td>
                                <td>労働時間</td>
                                <td>時間外</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td>{{$fixedWorkTime}}</td>
                                <td>{{$totalRestTime}}</td>
                                <td>{{$totalOverTime}}</td>
                                <td>{{$totalWorkTime}}</td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                </form>
                </div>
        </div>
@endsection