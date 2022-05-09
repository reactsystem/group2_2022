@extends('layouts.managerapp')

@section('bar')
@endsection

@section('content')
	<div class="container-fluid row justify-content-center">
		<div class="col-lg-12 card">
			<div class="card-header bg-light flex">
				<div class="dropdown">
					<button type="button" id="dropdown1"
						class="btn btn-light dropdown-toggle"
						data-toggle="dropdown"
						aria-haspopup="true"
						aria-expanded="false">
						営業部
					</button>
					<div class="dropdown-menu" aria-labelledby="dropdown1">
						<a class="dropdown-item" href="#">営業部</a>
						<a class="dropdown-item" href="#">管理部</a>
						<a class="dropdown-item" href="#">開発部</a>
					</div>
				</div>

				<form action="/management" method="GET">
					@csrf
					<input type="date" name="date" value="{{$date}}">
				</form>
				
				<button type="button" class="btn">< 前日</button>
				<button type="button" class="btn">翌日 ></button>

				<div class="right">
					<button type="button" class="btn btn-outline-primary">更新</button>
					<button type="button" class="btn btn-outline-secondary">ファイル出力</button>
				</div>
			</div>

			<div class="card-body">
				<table class="table table-striped">
					<thead class="thead-light">
						<tr><th></th><th>名前</th><th>勤務区分</th><th>開始</th><th>終了</th><th>休憩時間</th><th>労働時間</th><th>時間外</th><th>メモ</th></tr>
					</thead>

					<tr>
						<td>1</td>
						<td><a href="">山田太郎</a></td>
						<td>出勤</td>
						<td>9:25</td>
						<td>18:05</td>
						<td>00:45</td>
						<td>07:45</td>
						<td>00:00</td>
						<td>・勤怠管理システムの画面設計書を作成</td>
					</tr>
					@for ($i = 2; $i <= 10; $i++)
						<tr><td>{{$i}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
					@endfor
				</table>
			</div>
		</div>
	</div>
@endsection

@section('footer')
@endsection