@extends('layouts.app')

@section('css')
<link href="{{ asset('css/employees.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    @if (session()->has('message'))
    <div class="alert alert-primary" role="alert">
        {{session('message')}}
    </div>
    @endif

    <!-- 検索フォーム -->
    <form id="search" action="" method="get">
        <div class="d-flex mt-3 mb-2">
            表示件数：
            <select id="limit" name="disp_limit">
                @foreach($limit_disp as $limit)
                    <option id="limit_disp" value="{{$loop->index}}" @if(\Request::get('disp_limit') == $loop->index) selected @endif>{{$limit}}</option>
                @endforeach
            </select>   
    </form>
            @if(!empty($applications->appends(request()->input())->links()))
            <span class="links ml-5">
                {{$applications->appends(request()->input())->links('vendor.pagination.bootstrap-5')}}
            </span>
            <span class="ml-2 mt-2">{{ $applications->total() }}件中{{ $applications->firstItem() }}〜{{ $applications->lastItem() }} 件を表示</span>
            @endif
            <!-- 新規申請ボタン -->
            <a class="btn btn-success mb-3 employees-add ml-auto" href="{{ route('application.show', array_merge(Request::query(), ['date' => ''])) }}">新規申請</a>
        </div>
    
    <table id="application" class="table table-bordered text-center align-middle">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">対象日</th>
            <th scope="col">申請内容</th>
            <th scope="col">開始時間</th>
            <th scope="col">終了時間</th>
            <th scope="col">ステータス</th>
        </tr>
    </thead>
    <tbody>
        @foreach($applications as $application)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$application->date}}</td>
                <td>{{$application->applicationType->name}}</td>
                <td>{{$application->start_time}}</td>
                <td>{{$application->end_time}}</td>
                <td>
                    @if ($application->status == 0)
                    申請中
                    @elseif ($application->status == 1)
                    承認済み
                    @elseif ($application->status == 2)
                    却下済み
                    @elseif ($application->status == 3)
                    取り下げ済み
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
    </table>
</div>
<!-- Modal ---------------------------------------------------->
<!-- 申請承認フォーム -->
<div class="modal fade" id="modal-approval" tabindex="-1" aria-labelledby="label-fixed" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<form action="{{route('application.approve')}}" method="POST" id="approval">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title">申請承認フォーム</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<input type="hidden" name="id" id="app-id">
					<input type="hidden" name="department_id" id="app-dept-id">

					<!-- 申請者の名前 -->
					<div class="row mb-3">
						<label for="name" class="col-md-4 col-form-label text-md-end">申請者</label>
						<div class="col-md-6">
							<input type="text" name="name" id="app-name" class="form-control" readonly>
						</div>
					</div>

					<!-- 申請者の部署名 -->
					<div class="row mb-3">
						<label for="department" class="col-md-4 col-form-label text-md-end">部署名</label>
						<div class="col-md-6">
							<input type="text" name="department" id="app-dept" class="form-control" readonly>
						</div>
					</div>

					<!-- 申請種類 -->
					<div class="row mb-3">
						<label for="applied_content" class="col-md-4 col-form-label text-md-end">申請内容</label>
						<div class="col-md-6">
							<input type="text" name="applied_content" id="app-type" class="form-control" readonly>
						</div>
					</div>

					<!-- 申請理由 -->
					<div class="row mb-3">
						<label for="reason" class="col-md-4 col-form-label text-md-end">申請理由</label>
						<div class="col-md-6">
							<textarea name="reason" id="app-reason" class="form-control" readonly></textarea>
						</div>
					</div>

					<!-- 申請したい日 -->
					<div class="row mb-3">
						<label for="date" class="col-md-4 col-form-label text-md-end">申請日</label>
						<div class="col-md-6">
							<input type="text" name="date" id="app-date" class="form-control" readonly>
						</div>
					</div>

					<!-- 開始時間 -->
					<div class="row mb-3">
						<label for="start_time" class="col-md-4 col-form-label text-md-end">開始時間</label>
						<div class="col-md-6">
							<input type="text" name="start_time" id="app-start" class="form-control" readonly>
						</div>
					</div>

					<!-- 終了時間 -->
					<div class="row mb-3">
						<label for="end_time" class="col-md-4 col-form-label text-md-end">終了時間</label>
						<div class="col-md-6">
							<input type="text" name="end_time" id="app-end" class="form-control" readonly>
						</div>
					</div>

					<!-- 承認/差し戻しコメント -->
					<div class="row mb-3">
						<label for="comment" class="col-md-4 col-form-label text-md-end">コメント</label>
						<div class="col-md-6">
							<textarea class="form-control" name="comment" id="comment" autocomplete="comment" autofocus>{{ old('comment') }}</textarea>
							<p class="help-block">※60文字以内で書いてください</p>
							<div class="commentError d-none" role="alert">
								<strong class="commentErrorMsg text-danger"></strong>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<!-- 承認/却下/取り下げボタン -->
					<div class="col-md-8 offset-md-4">
						<input type="hidden" name="result" value="承認" id="value-approve" disabled>
						<input type="hidden" name="result" value="却下" id="value-reject" disabled>
						<input type="hidden" name="result" value="取り下げ" id="value-stop" disabled>
						<button type="button" class="btn btn-primary ml-2 mr-4" id="btn-approve">
							承認
						</button>
						<button type="button" class="btn btn-danger ml-4 mr-3" id="btn-reject">
							却下
						</button>
						<button type="button" class="btn btn-secondary ml-4 mr-3" id="btn-stop">
							取り下げ
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-------------------------------------------------end Modal -->

@endsection

@section('js')
<script src="{{asset('js/show_self_application.js')}}"></script>
@endsection