@extends('layouts.app')

@section('css')
<link href="{{ asset('css/input.css') }}" rel="stylesheet">
@endsection

    @section('content')
    @if (session()->has('message'))
    <div class="alert alert-primary" role="alert">
        {{session('message')}}
    </div>
    @endif

    @if ($errors->has('start_time'))
    <div class="alert alert-danger" role="alert">
        {{ $errors->first('start_time') }}
    </div>
    @endif

    @if ($errors->has('left_time'))
    <div class="alert alert-danger" role="alert">
        {{ $errors->first('left_time') }}
    </div>
    @endif

        <div class="container">
            <div class="contents">

                <div class="select_month col">
                    <p class="mr-4">{{$user->name}}さんの勤務表</p>
                    <form method="GET" id="select" action="" class="form-inline mb-2">
                        @csrf
                        <input type="hidden" name="user_id" value={{$user->id}}>
                        <div class="form-group">
                            <select name="month" class="form-select col mr-2" aria-label="Default select example" onchange="submit_form()">
                                @php
                                    $last_month = $month->copy()->subMonthNoOverflow();
                                    $next_month = $month->copy()->addMonthNoOverflow();
                                    $month->subMonthNoOverflow(7);
                                @endphp
                                @for ($i =1; $i <= 13; $i++)
                                    @php $month->addMonthNoOverflow(); @endphp
                                    <option value={{$month}}
                                    @if ($i == 7)
                                        selected
                                    @endif
                                >{{$month->isoFormat('YYYY年M月')}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="month" value={{$last_month}} class="btn btn-outline-dark btn-sm mr-2">＜前月へ</button>
                        </div>
                        <div class="form-group">
                        <button type="submit" name="month" value={{$next_month}} class="btn btn-outline-dark btn-sm">翌月へ＞</button>
                        </div>
                    </form>
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
                        <td><div class="collapse collapse0" id="weekday_sum_info"></td>
                        <td><div class="collapse collapse0" id="rest_info"></td>
                        <td><div class="collapse collapse0" id="worked_info"></td>
                        <td><div class="collapse collapse0" id="over_info"></td>
                    </tr>
                    </tbody>
                </table>

                <table class="table col-7 table-sm">
                    <thead>
                        <tr class="table-success">
                            <th colspan="5" scope="col">
                                <button class="btn" type="button" data-toggle="collapse" data-target=".collapse1" aria-expanded="false" style="width: 100%; text-align: left;">
                                    勤務区分項目
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        @foreach ($work_types as $work_type)
                        <td><div class="collapse collapse1">{{$work_type->name}}</td>
                            @if ($loop->iteration == 5)
                            @break
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($work_types as $work_type)
                        <td><div class="collapse collapse1">{{$work_times->where('work_type_id', $work_type->id)->count()}}</td>
                            @if ($loop->iteration == 5)
                            @break
                            @endif
                        @endforeach
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
                        <td><div class="collapse collapse2">{{$work_times->where('work_type_id', 6)->count()}}</td>
                        <td><div class="collapse collapse2">{{$work_times->where('work_type_id', 7)->count()}}</td>
                        <td><div class="collapse collapse2">{{$paid_leaves->left_days}}</td>
                        <td><div class="collapse collapse2"></td>
                    </tr>
                    </tbody>
                </table>

                <div class="input_form">
                <form action="" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg mb-3">更新する</button>

                    <table class="table table-bordered table-sm" id="input_table">
                        <thead>
                        <tr class="table-info">
                            <th scope="col" style="width: 10%">日付</th>
                            <th scope="col" style="width: 10%">勤務区分</th>
                            <th scope="col" style="width: 8%">開始</th>
                            <th scope="col" style="width: 8%">終了</th>
                            <th scope="col" style="width: 8%">休憩時間</th>
                            <th scope="col" style="width: 8%">労働時間</th>
                            <th scope="col" style="width: 8%">時間外</th>
                            <th scope="col">メモ</th>
                        </tr>
                        </thead>

                        <tbody>
                        @for($i = 1; $i <= $daysInMonth; $i++)
                            <tr>
                                <td
                                @if ($dt->isoFormat('ddd') === '土')
                                    style="color: blue;"
                                @elseif ($dt->isoFormat('ddd') === '日' || $holidays->isHoliday($dt))
                                    style="color: red;"
                                @else
                                    id="weekday"
                                @endif
                                >
                                {{$dt->isoFormat('MM/DD(ddd)')}}
                                <input type="hidden" name="user_id[]" value={{$user->id}}>
                                <input type="hidden" name="date[]" value={{$dt->isoFormat('YYYY-MM-DD')}}>
                                </td>
                                @php $work_time = $work_times->where('date', $dt->isoFormat('YYYY-MM-DD'))->first(); @endphp
                                    @if ($work_time !== NULL)
                                        {{-- 時刻計算処理用 --}}
                                        @php $start_time = strtotime($work_time->start_time);
                                            $left_time = strtotime($work_time->left_time);
                                        @endphp

                                        <td>
                                            <select name="work_type[]">
                                                @foreach ($work_types as $work_type)
                                                    <option value={{$work_type->id}}
                                                    @if ($work_time->work_type_id == $work_type->id)
                                                        selected
                                                    @endif
                                                    @if ($work_type->name == '有給休暇' || $work_type->name == '特別休暇')
                                                        hidden
                                                    @endif
                                                    >{{$work_type->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="start_time[]" size="5"
                                            @isset ($work_time->start_time)
                                            value={{date('H:i', $start_time)}}
                                            @endisset>
                                        </td>
                                        <td><input type="text" name="left_time[]" size="5"
                                            @isset ($work_time->left_time)
                                            value={{date('H:i', $left_time)}}
                                            @endisset>
                                        </td>
                                        <td>
                                            @isset ($work_time->left_time)
                                                @if (date('H:i', $left_time) < '18:15')
                                                    00:45
                                                @elseif (date('H:i', $left_time) >= '18:15')
                                                    01:00
                                                @endif
                                            @endisset
                                        </td>
                                        <td>
                                            @isset ($work_time->left_time)

                                            {{-- 遅刻した場合 --}}
                                            @if (date('H:i', $start_time) >= '09:30')
                                                @if (date('H:i', $left_time) < '18:00')
                                                    @php $worked_time = strtotime("-45 min", $left_time) - $start_time; @endphp
                                                    {{gmdate("H:i", $worked_time)}}
                                                @elseif (date('H:i', $left_time) >= '18:00' && date('H:i', $left_time) < '18:15')
                                                    07:45
                                                @elseif (date('H:i', $left_time) >= '18:15')
                                                    @php $worked_time = strtotime("-1 hours", $left_time) - $start_time; @endphp
                                                    {{gmdate("H:i", $worked_time)}}
                                                @endif

                                            {{-- 始業開始よりも早く出勤した場合 --}}
                                            @elseif (date('H:i', $start_time) < '09:30')
                                                @if (date('H:i', $left_time) < '18:00')
                                                    @php $worked_time = strtotime("-45 min", $left_time) - strtotime($fixed_time->start_time); @endphp
                                                    {{gmdate("H:i", $worked_time)}}
                                                @elseif (date('H:i', $left_time) >= '18:00' && date('H:i', $left_time) < '18:15')
                                                    07:45
                                                @elseif (date('H:i', $left_time) >= '18:15')
                                                    @php $worked_time = strtotime("-1 hours", $left_time) - strtotime($fixed_time->start_time); @endphp
                                                    {{gmdate("H:i", $worked_time)}}
                                                @endif
                                            @endif
                                        @endisset
                                        </td>
                                        <td>
                                            @if (date('H:i', $left_time) < '18:15')
                                            00:00
                                        @elseif (date('H:i', $left_time) >= '18:15')
                                            {{-- 遅刻した場合 --}}
                                            @if (date('H:i', $start_time) >= '09:30')
                                                @php $worked_time = strtotime("-1 hours", $left_time) - $start_time; @endphp
                                                {{gmdate("H:i", strtotime("-45 min -7 hours", $worked_time))}}
                                            {{-- 始業開始よりも早く出勤した場合 --}}
                                            @elseif (date('H:i', $start_time) < '09:30')
                                                @php $worked_time = strtotime("-1 hours", $left_time) - strtotime($fixed_time->start_time); @endphp
                                                {{gmdate("H:i", strtotime("-45 min -7 hours", $worked_time))}}
                                            @endif
                                        @endif
                                        </td>
                                        <td>{{$work_time->description}}</td>
                                    @else
                                        <td>
                                            <select name="work_type[]">
                                                <option></option>
                                                @foreach ($work_types as $work_type)
                                                <option value={{$work_type->id}}>{{$work_type->name}}</option>
                                                    @if ($loop->iteration == 5)
                                                    @break
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="start_time[]" size="5"></td>
                                        <td><input type="text" name="left_time[]" size="5"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    @endif
                            </tr>
                            @php $dt->addDay(); @endphp
                        @endfor
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
                            <td id="weekday_sum"></td>
                            <td id="rest"></td>
                            <td id="worked"></td>
                            <td id="over"></td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/personal_mgmt.js') }}"></script>
@endsection