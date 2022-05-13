下記のとおり申請結果を通知いたします。

<p>【申請者】{{$data['name']}}さん</p>
<p>【申請結果】{{$data['result']}} /決裁者：{{$data['user']}}さん/</p>
<p>【申請内容】{{$data['applied_content']}}</p>
<p>【申請理由】{{$data['reason']}}</p>
<p>【申請日】{{$data['date']}}</p>
@isset ($data['start_time'])
<p>【開始時間】{{$data['start_time']}}</p>
@endisset
@isset ($data['end_time'])
<p>【終了時間】{{$data['end_time']}}</p>
@endisset
<p>決裁者からのコメント：{!! nl2br( $data['comment'] ) !!}</p>