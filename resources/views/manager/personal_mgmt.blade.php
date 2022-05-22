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
                    <p class="mr-4 under">{{$user->name}}さんの勤務表</p>
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
                        <td><div class="collapse collapse0">所定時間</div></td>
                        <td><div class="collapse collapse0">休憩時間</div></td>
                        <td><div class="collapse collapse0">労働時間</div></td>
                        <td><div class="collapse collapse0">時間外</div></td>
                    </tr>
                    <tr>
                        <td><div class="collapse collapse0" id="weekday_sum_info"></div></td>
                        <td><div class="collapse collapse0" id="rest_info"></div></td>
                        <td><div class="collapse collapse0" id="worked_info"></div></td>
                        <td><div class="collapse collapse0" id="over_info"></div></td>
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
                            <td>
                                <div class="collapse collapse1">
                                    {{$work_type->name}}
                                </div>
                            </td>
                            @if ($loop->iteration == 5)
                            @break
                            @endif
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($work_types as $work_type)
                            <td>
                                <div class="collapse collapse1">
                                    {{$work_times->where('work_type_id', $work_type->id)->count()}}
                                </div>
                            </td>
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
                        <td><div class="collapse collapse2">当月の有給休暇取得日数</div></td>
                        <td><div class="collapse collapse2">有給休暇残り日数</div></td>
                        <td><div class="collapse collapse2">特別休暇取得日数</div></td>
                        <td><div class="collapse collapse2"></div></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="collapse collapse2">
                                済：{{$work_times->where('date', '<', $today->isoFormat('YYYY-MM-DD'))->where('work_type_id', 6)->count()}}
                                /予定：{{$work_times->where('date', '>=', $today->isoFormat('YYYY-MM-DD'))->where('work_type_id', 6)->count()}}
                            </div>
                        </td>
                    </td>
                    <td><div class="collapse collapse2">
                        @if(!empty($paid_leave_sum))
                            {{$paid_leave_sum}}
                        @else
                            0
                        @endif
                    </td>
                    <td>
                        <div class="collapse collapse2">
                            済：{{$work_times->where('date', '<', $today->isoFormat('YYYY-MM-DD'))->where('work_type_id', 7)->count()}}
                            /予定：{{$work_times->where('date', '>=', $today->isoFormat('YYYY-MM-DD'))->where('work_type_id', 7)->count()}}
                        </div>
                    <td><div class="collapse collapse2"></td>
                    </tr>
                    </tbody>
                </table>

                <div class="input_form" style="font-size: 12px;">
                <form action="" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary mb-3">更新する</button>

                    <table class="table table-bordered table-sm" id="input_table">
                        <thead>
                        <tr class="table-info" style="text-align: center;">
                            <th scope="col" style="width: 8%">日付</th>
                            <th scope="col" style="width: 8%">勤務区分</th>
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
                                        @php
                                            $start_time = strtotime($work_time->start_time);
                                            $left_time = strtotime($work_time->left_time);
                                            $rest_time = strtotime($work_time->rest_time);
                                            $over_time = strtotime($work_time->over_time);
                                            $fixed_start = strtotime($fixed_time->start_time);
                                            $fixed_left = strtotime($fixed_time->left_time);
                                            $fixed_left_over = strtotime("+15 min", strtotime($fixed_time->left_time));

                                            // 勤務時間から差し引く休憩時間を取得
                                            $from = strtotime('00:00:00');
                                            $end = strtotime($work_time->rest_time);
                                            $minutes = ($end - $from) / 60;
                                            $calculate_rest = "-" . $minutes . "min";
                                        @endphp

                                        <td>
                                            {{-- 過去の有給休暇は変更不可にする --}}
                                            @if ($work_time->date < $today->isoFormat('YYYY-MM-DD') && $work_time->work_type_id == 6)
                                            <select name="work_type[]" style="pointer-events: none; background: gray;" tabindex="-1">
                                            @else

                                            <select name="work_type[]">
                                            @endif
                                                    <option value='delete'></option>
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
                                            {{date('H:i', $rest_time)}}
                                            @endisset
                                        </td>
                                        <td>
                                            @isset ($work_time->left_time)
                                                {{-- 遅刻した場合 --}}
                                                @if ($start_time >= $fixed_start)
                                                    {{-- かつ定時退勤の場合 --}}
                                                    @if ($left_time >= $fixed_left && $left_time < $fixed_left_over)
                                                        @php $worked_time = strtotime($calculate_rest, $fixed_left) - $start_time; @endphp
                                                        {{gmdate("H:i", $worked_time)}}
                                                    {{-- その他の場合 --}}
                                                    @else
                                                        @php $worked_time = strtotime($calculate_rest, $left_time) - $start_time; @endphp
                                                        {{gmdate("H:i", $worked_time)}}
                                                    @endif

                                                {{-- 始業開始よりも早く出勤した場合 --}}
                                                @elseif ($start_time < $fixed_start)
                                                    {{-- かつ定時退勤の場合 --}}
                                                    @if ($left_time >= $fixed_left && $left_time < $fixed_left_over)
                                                        @php $worked_time = strtotime($calculate_rest, $fixed_left) - $fixed_start; @endphp
                                                        {{gmdate("H:i", $worked_time)}}
                                                    {{-- その他の場合 --}}
                                                    @else
                                                        @php $worked_time = strtotime($calculate_rest, $left_time) - $fixed_start; @endphp
                                                        {{gmdate("H:i", $worked_time)}}
                                                    @endif
                                                @endif
                                            @endisset

                                            {{-- 「有給休暇」「特別休暇」は有給扱いのため、定時の勤務時間を表示する --}}
                                            @if ($work_time->workType->name == '有給休暇' || $work_time->workType->name == '特別休暇')
                                                @php $worked_time = strtotime($calculate_rest, $fixed_left) - $fixed_start; @endphp
                                                {{gmdate("H:i", $worked_time)}}
                                            @endif
                                        </td>
                                        <td>
                                            @isset ($work_time->over_time)
                                            {{date('H:i', $over_time)}}
                                            @else
                                            00:00
                                            @endisset
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