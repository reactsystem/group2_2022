@extends('layouts.app')

@section('css')
<link href="{{ asset('css/input.css') }}" rel="stylesheet">
@endsection

    @section('content')
        @if (session('sent_form'))
            <div class="alert alert-success text-center" role="alert">
                {{ session('sent_form') }}
            </div>
        @endif
        <div class="container">
            <div class="left col-3">
                
            <!-- 左カラム -->
                <div class="button_form">
                    <p style="background-color: #99FFFF;">
                        {{$today->isoFormat('YYYY/MM/DD(ddd)')}}
                    </p>
                    @if (session('message'))
                        <div class="alert alert-danger">
                            {{ session('message') }}
                        </div>
                    @endif
                        <form action="/" method="POST">
                            @csrf
                                <input type="hidden" name="user_id" value={{$user->id}}>
                                <button type="submit" class="btn btn-info btn-lg mb-3" name="start_time" value="#">出勤</button>
                                <button type="submit" class="btn btn-warning btn-lg mb-3" name="left_time" value="#">退勤</button>
                                <div class="form-floating">
                                    <textarea class="form-control" name="description" placeholder="Leave a comment here" id="description" style="height: 100px; font-size: 10px;"
                                    >@isset($description){{$description->description}}@endisset</textarea>
                                    <label for="description" style="font-size: 10px;">60文字以内で打刻メモを入力できます</label>
                                </div>
                                @if ($errors->has('description'))
                                <div class="alert alert-danger" role="alert" style="font-size: 10px;">
                                    {{ $errors->first('description') }}
                                </div>
                                @endif
                                <button type="submit" name="description_submit" value="#" class="btn btn-secondary btn-sm mt-3">打刻メモを送信</button>
                        </form>
                </div>
                <div class="info_form table-sm">
                    <table class="table table-info table-striped" style="font-size: 10px;">
                        <thead>
                        <tr>
                            <th scope="col" style="width: 50%">当月の有給取得日数</th>
                            <th scope="col">有給残り日数</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                済：{{$work_times->where('date', '<=', $today->isoFormat('YYYY-MM-DD'))->where('work_type_id', 6)->count()}}日
                                /予定：{{$work_times->where('date', '>', $today->isoFormat('YYYY-MM-DD'))->where('work_type_id', 6)->count()}}日
                            </td>
                            <td>
                                @if(!empty($paid_leave_sum))
                                    {{$paid_leave_sum}}日
                                @else
                                    0日
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 右カラム -->
            <div class="contents">
                <form method="GET" id="select" action="" class="form-inline mb-2">
                    @csrf
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
                                >{{$month->isoFormat('YYYY年M月')}}
                                </option>
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

                <table class="table table-bordered table-sm" style="font-size: 12px;" id="input_table">
                    <thead>
                        <tr class="table-info" style="text-align: center;">
                            <th scope="col" style="width: 8%">日付</th>
                            <th scope="col" style="width: 8%">勤務区分</th>
                            <th scope="col" style="width: 8%">開始</th>
                            <th scope="col" style="width: 8%">終了</th>
                            <th scope="col" style="width: 8%">休憩時間</th>
                            <th scope="col" style="width: 8%">労働時間</th>
                            <th scope="col" style="width: 7%">時間外</th>
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

                                        // 退勤時刻を丸める範囲の時刻を取得
                                        $fixed_left_over = strtotime($fixed_time->getRoundTime(), strtotime($fixed_time->left_time));

                                        // 勤務時間から差し引く休憩時間を取得
                                        $from = strtotime('00:00:00');
                                        $end = strtotime($work_time->rest_time);
                                        $minutes = ($end - $from) / 60;
                                        $calculate_rest = "-" . $minutes . "min";

                                    @endphp

                                    <td>{{$work_time->workType->name}}</td>
                                    <td>
                                        @isset ($work_time->start_time)
                                        {{date('H:i', $start_time)}}
                                        @endisset
                                    </td>
                                    <td>
                                        @isset ($work_time->left_time)
                                        {{date('H:i', $left_time)}}
                                        @endisset
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

                                        {{-- 「有給休暇」「特別休暇」は有給扱いのため、既定の労働時間を表示する --}}
                                        @if ($work_time->workType->name == '有給休暇' || $work_time->workType->name == '特別休暇')
                                            {{$fixed_work_time}}
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
                                    <td></td>
                                    <td></td>
                                    <td></td>
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
            </div>
        </div>
        <script> const fixed_work_time = @json($fixed_work_time); </script>
        <script src="{{ asset('js/input.js') }}"></script>
    @endsection