<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 
    </head>
    <style>
    .container{
        display: flex;
    }
    .left{
        width: 300px;
        text-align: center;
    }
    .left table{
        margin:auto;
    }
    .contents{
        flex: 1;
    }
    .select_month {
        display: flex;
    }
    </style>
    <header>
        <h4>[ロゴ] [各種申請] [勤怠管理]　　〇〇△△さん</h4>
    </header>
    <body>
        <div class="container">
            <div class="left">
            <!-- 左カラム -->
                <div class="button_form">
                    <p>@php $date = new DateTime();
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
                                <button type="submit" name="start_time" value="">出勤</button>
                                <button type="submit" name="left_time" value="">退勤</button>
                                <textarea name="discription" placeholder="打刻メモを入力できます" rows="8" cols="30"></textarea>
                </div>
                <div class="info_form">
                    <table border="1">
                        <tr>
                            <th>当月の有給取得日数</th>
                            <th>有給残り日数</th>
                        </tr>
                        <tr>
                            <td>0日</td>
                            <td>20日</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="contents">
            <!-- 右カラム -->
                <div class="select_month">
                <form action="" method="POST">
                    <select name="month">
                        <option value="">２０２２年５月</option>
                    </select>
                    <p>＜前月へ　翌月へ＞</p>
                </form>
                </div>
                <div class="input_form">
                <form action="" method="POST">
                    @csrf
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