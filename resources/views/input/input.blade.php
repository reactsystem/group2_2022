@extends('layouts.app')

@section('css')
<link href="{{ asset('css/input.css') }}" rel="stylesheet">
@endsection

    <header>
        {{-- テスト用 --}}
        {{-- @php var_dump($work_times); @endphp --}}
    </header>
    @section('content')
        <div class="container">
            <div class="left col-3">
                
            <!-- 左カラム -->
                <div class="button_form">
                    <p style="background-color: #99FFFF;">
                        @php 
                            echo $today->isoFormat('YYYY/MM/DD(ddd)');
                        @endphp
                    </p>
                        <form action="" method="POST">
                            @csrf
                                <button type="buttom" class="btn btn-info btn-lg mb-3" name="start_time" value="">出勤</button>
                                <button type="buttom" class="btn btn-warning btn-lg mb-3" name="left_time" value="">退勤</button>
                                <div class="form-floating">
                                    <textarea class="form-control" name="description" placeholder="Leave a comment here" id="description" style="height: 100px"></textarea>
                                    <label for="description">打刻メモを入力できます</label>
                                </div>
                                <button type="buttom" class="btn btn-secondary btn-sm mt-3" name="left_time" value="">打刻メモを送信</button>
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
                            <td></td>
                            <td>{{$paid_leaves->left_days}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 右カラム -->
            <div class="contents">
                <div class="select_month col">
                <form action="" method="POST">
                    <select name="month" class="form-select col mr-2" aria-label="Default select example">
                        <option value="" selected>２０２２年５月</option>
                    </select>
                </form>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                          <li class="page-item"><a class="page-link" href="#">＜前月へ</a></li>
                          <li class="page-item"><a class="page-link" href="#">翌月へ＞</a></li>
                        </ul>
                      </nav>
                </div>
                <div class="input_form">
                <form action="" method="POST">
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
                                    <td>
                                            {{$work_time->workType->name}}
                                    </td>
                                    <td>
                                            {{$work_time->start_time}}
                                    </td>
                                    <td>
                                            {{$work_time->left_time}}
                                    </td>
                                    <td>
                                            {{$fixed_time->rest_time}}
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                            {{$work_time->over_time}}
                                    </td>
                                    <td>
                                            {{$work_time->description}}
                                    </td>
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