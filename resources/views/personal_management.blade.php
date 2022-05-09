@extends('layouts.app')

    <header>
    </header>
    @section('content')

        <div class="container">
            <div class="contents">

                <div class="select_month col">
                    <p class="mr-4">◎◎△△さんの勤務表</p>
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
                        <td><div class="collapse collapse0">18:05</td>
                        <td><div class="collapse collapse0">00:45</td>
                        <td><div class="collapse collapse0">07:45</td>
                        <td><div class="collapse collapse0">00:00</td>
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
                        <td><div class="collapse collapse2">0</td>
                        <td><div class="collapse collapse2">0</td>
                        <td><div class="collapse collapse2">20</td>
                        <td><div class="collapse collapse2"></td>
                    </tr>
                    </tbody>
                </table>

                <div class="input_form">
                <form action="" method="POST">
                    @csrf
                    <button type="button" class="btn btn-primary btn-lg mb-3">更新する</button>

                        <table class="table table-bordered col-10 table-sm">
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
                                <td><select name="work_type">
                                    <option value=""></option>
                                    <option value="1" selected>出勤</option>
                                    <option value="2">欠勤</option>
                                    <option value="3">遅刻</option>
                                    <option value="4">早退</option>
                                    </select>
                                </td>
                                <td><input type="text" name="start_time" size="5" value="09:27"></td>
                                <td><input type="text" name="left_time" size="5" value="18:05"></td>
                                <td>00:45</td>
                                <td>07:45</td>
                                <td>00:00</td>
                                <td>・勤怠管理システムの画面設計書を作成</td>
                            </tr>
                            @for($n = 2; $n <= 31; $n++)
                            <tr>
                                <td>5/{{$n}}(月)</td>
                                <td><select name="work_type">
                                    <option value=""></option>
                                    <option value="1">出勤</option>
                                    <option value="2">欠勤</option>
                                    <option value="3">遅刻</option>
                                    <option value="4">早退</option>
                                    </select>
                                </td>
                                <td><input type="text" name="start_time" size="5"></td>
                                <td><input type="text" name="left_time" size="5"></td>
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