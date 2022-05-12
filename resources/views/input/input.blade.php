@extends('layouts.app')

@section('css')
<link href="{{ asset('css/input.css') }}" rel="stylesheet">
@endsection

    <header>
        {{-- テスト用 --}}
        {{-- @php var_dump($work_times); @endphp --}}
    </header>
    @section('content')
        @if (session('sended_form'))
            <div class="alert alert-success text-center" role="alert">
                {{ session('sended_form') }}
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
                                    <textarea class="form-control" name="description" placeholder="Leave a comment here" id="description" style="height: 100px"
                                    >@isset($description){{$description->description}}@endisset</textarea>
                                    <label for="description">打刻メモを入力できます</label>
                                </div>
                                <button type="submit" name="description_submit" value="#" class="btn btn-secondary btn-sm mt-3">打刻メモを送信</button>
                        </form>
                </div>
                <div class="info_form table-sm">
                    <table class="table table-info table-striped" style="font-size : 10px;">
                        <thead>
                        <tr>
                            <th scope="col">当月の有給取得日数</th>
                            <th scope="col">有給残り日数</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{$count_paid_leaves}}</td>
                            <td>{{$paid_leaves->left_days}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 右カラム -->
            <div class="contents">
                <form method="POST" id="select" action="/select_month" class="form-inline">
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
                                <option value="@php echo $month; @endphp"
                                @if ($i == 7)
                                    selected
                                @endif
                            >{{$month->isoFormat('YYYY年M月')}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="month" value="@php echo $last_month @endphp" class="btn btn-outline-dark btn-sm mr-2">＜前月へ</button>
                    </div>
                    <div class="form-group">
                    <button type="submit" name="month" value="@php echo $next_month @endphp" class="btn btn-outline-dark btn-sm">翌月へ＞</button>
                    </div>
                </form>
                    {{-- <nav aria-label="Page navigation example">
                        <ul class="pagination">
                          <li class="page-item"><a class="page-link" href="#">＜前月へ</a></li>
                          <li class="page-item"><a class="page-link" href="#">翌月へ＞</a></li>
                        </ul>
                      </nav> --}}
                <div class="input_form">
                <form action="/" method="POST">
                    @csrf
                        <table class="table table-bordered table-sm">
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
                            @for($i = 1; $i <= $daysInMonth; $i++)
                                <tr>
                                    <td>
                                        @php echo $dt->isoFormat('MM/DD(ddd)'); @endphp
                                    </td>
                                    @foreach ($work_times as $work_time)
                                        @if ($work_time->date == $dt->isoFormat('YYYY-MM-DD'))
                                            <td>{{$work_time->workType->name}}</td>
                                            <td>{{$work_time->start_time}}</td>
                                            <td>{{$work_time->left_time}}</td>
                                            <td>{{$fixed_time->rest_time}}</td>
                                            <td></td>
                                            <td>{{$work_time->over_time}}</td>
                                            <td>{{$work_time->description}}</td>
                                        @endif
                                    @endforeach
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
                                <td>18:05</td>
                                <td>00:45</td>
                                <td>07:45</td>
                                <td>00:00</td>
                                <td></td>
                            </tr>
                            </tfoot>

                        </table>
                </form>
                </div>
        </div>
    @endsection