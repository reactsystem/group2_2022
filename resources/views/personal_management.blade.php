<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 
    </head>
    <style>
    .select_month {
        display: flex;
    }
    .select_month form {
        display: flex;
    }
    .select_month form select{
        margin: 10px 20px;
    }
    .input_form {
        clear: both;
    }
    .info_table {
        table-layout: auto;
    }
    .info_table .item {
        width: 150px;
    }
    .info_table th {
        border: 1px solid green;
    }
    .info_table td {
        border: 1px solid green;
        text-align: center;
    }
    button {
        margin: 10px 10px;
    }

    </style>
    <header>
        <h4>[ロゴ] [勤怠入力] [各種申請]　　[勤怠管理] [申請承認] [社員管理] [マスタ管理]　　□□◎◎さん
        </h4>
    </header>
    <body>
        <div class="container">
            <div class="contents">
                <div class="select_month">
                    <p>〇〇△△さん の勤務表</p>
                    <form action="" method="POST">
                        <select name="month">
                            <option value="">２０２２年５月</option>
                        </select>
                        <p>＜前月へ　翌月へ＞</p>
                    </form>
                </div>

                <table class="info_table">
                    <tr><th colspan="4">勤務時間項目</th></tr>
                    <tr>
                        <th class="item">所定時間</th>
                        <th class="item">休憩時間</th>
                        <th class="item">労働時間</th>
                        <th class="item">時間外</th>
                    </tr>
                    <tr>
                        <td>18:05</td>
                        <td>00:45</td>
                        <td>07:45</td>
                        <td>00:00</td>
                    </tr>
                    <tr><th colspan="4">勤務区分項目</th></tr>
                    <tr>
                        <th class="item">出勤</th>
                        <th class="item">欠勤</th>
                        <th class="item">遅刻</th>
                        <th class="item">早退</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr><th colspan="4">休暇項目</th></tr>
                    <tr>
                        <th class="item">有給休暇取得日数</th>
                        <th class="item">特別休暇取得日数</th>
                        <th class="item">有給休暇残り日数</th>
                        <th class="item"></th>
                    </tr>
                    <tr>
                        <td>0</td>
                        <td>0</td>
                        <td>20</td>
                        <td></td>
                    </tr>
                </table>

                <div class="input_form">
                <form action="" method="POST">
                    @csrf
                    <button>更新する</button>
                        <table border="1">
                            <tr>
                                <th>日付</th>
                                <th>勤務区分</th>
                                <th>開始</th>
                                <th>終了</th>
                                <th>休憩時間</th>
                                <th>労働時間</th>
                                <th>時間外</th>
                                <th>メモ</th>
                            </tr>
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

                            <tr>
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

                        </table>
                </form>
                </div>
        </div>
    </body>
</html>