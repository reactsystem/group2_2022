@extends('layouts.app')

@section('css')
<link href="{{ asset('css/input.css') }}" rel="stylesheet">
@endsection

    <header>
    </header>
    @section('content')
        <div class="container">
            <div class="left col-3">
            <!-- 左カラム -->
                <div class="button_form">
                    <p style="background-color: #99FFFF;">@php $date = new DateTime();
                    echo $date->format('Y年n月j日'); 

                    $week = [
                    '日', //0
                    '月', //1
                    '火', //2
                    '水', //3
                    '木', //4
                    '金', //5
                    '土', //6
                    ];
                    
                    $date = date('w');
                    
                    echo '(' . $week[$date] . ')';
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
                            <td>0日</td>
                            <td>20日</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="contents">
            <!-- 右カラム -->
                <div class="select_month col">
                <form action="" method="POST">
                    <select name="month" class="form-select col-md-3 mb-4" aria-label="Default select example">
                        <option value="">２０２２年５月</option>
                    </select>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                          <li class="page-item"><a class="page-link" href="#">＜前月へ</a></li>
                          <li class="page-item"><a class="page-link" href="#">翌月へ＞</a></li>
                        </ul>
                      </nav>
                </form>
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
                            <tr>
                                <td>5/1(月)</td>
                                <td>出勤</td>
                                <td>09:25</td>
                                <td>18:05</td>
                                <td>00:45</td>
                                <td>07:45</td>
                                <td>00:00</td>
                                <td>・勤怠管理システムの画面設計書を作成</td>
                            </tr>
                            @for($n = 2; $n <= 31; $n++)
                            <tr>
                                <td>5/{{$n}}(月)</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
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