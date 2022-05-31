<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use App\Models\FixedTime;
use App\Models\User;
use App\Models\WorkTime;
use Yasumi\Yasumi;

class DepartmentMgmtController extends Controller
{
	/* 部署別勤怠管理フォームの表示 ------------------------------*/
	public function index(Request $request)
	{
		// 表示する日付の取得
		$date = Carbon::today();
		if(isset($request->date))
		{
			$date = new Carbon($request->date);
		}

		// 表示する部署の取得
		$user = User::where('email', Auth::user()->email)->first();
		$dept = Department::where('id', $user->department_id)->first();
		if(isset($request->department))
		{
			// 部署選択で全てを選択されている場合
			if($request->department === '0')
			{
				$dept->id = 0;
				$dept->name = '全て';
			}
			else
			{
				$dept = Department::where('id', $request->department)->first();
			}
		}

		// 表示する件数の取得
		$disp_limit = $request->disp_limit;

		// 各種テーブルの取得
		$departments = Department::whereNull('deleted_at')->get();
		$fixed_time = FixedTime::first();

		// 部署選択で全てを選択されている場合
		if($dept->id === 0)
		{
			$users = User::whereNull('leaving')->paginate($disp_limit);
			if($disp_limit === null)
			{
				$users = User::whereNull('leaving')->get();
			}
			$work_times = WorkTime::where('date', $date->copy()->toDateString())->get();
		}
		else
		{
			$users = User::where('department_id', $dept->id)->whereNull('leaving')->paginate($disp_limit);
			if($disp_limit === null)
			{
				$users = User::where('department_id', $dept->id)->whereNull('leaving')->get();
			}
			$work_times = WorkTime::where('date', $date->copy()->toDateString())
				->whereHas('user', function($query) use($dept)
					{
						$query->where('department_id', $dept->id);
					})
				->get();
		}

		// 祝日の取得
		$holidays = Yasumi::create('Japan', $date->year, 'ja_JP');

		// csv プレビュー用
		$csv = $this->getExportData($dept->id, $date);

		return view('manager.department_mgmt', compact(
			'date',
			'dept',
			'departments',
			'fixed_time',
			'holidays',
			'users',
			'work_times',
			'disp_limit',
			'csv',
		));
	}
	/*============================================ end function ==*/

	/* download csv ----------------------------------------------*/
	public function export(Request $request)
	{
		// export csv
		$this->workTimeExport($request->file_name, $request->department, $request->date);

		// downloadできなかった場合のみここに戻ってくる
		$param = [
			'date' => $request->date,
			'department' => $request->department,
			'disp_limit' => $request->disp_limit,
		];
		return redirect()->route('mgmt.dept.post', $param);
	}
	/*============================================ end function ==*/

	/* export csv ------------------------------------------------*/
	private function workTimeExport($file_name, $department_id, $date)
	{
		// csv header
		$header = [
			'社員番号',	
			'社員名',
			'出勤日数',
			'労働時間',
			'時間外労働時間',
			'有給休暇取得日数',
		];
		
		// csv data
		$data = $this->getExportData($department_id, $date);

		// file path
		$path = storage_path('app/').$file_name.'.csv';

		/* export csv ----------------*/
		// ファイルを書き込みで開く
		$stream = fopen($path, 'w');

		// ファイルに書き込む
		if($stream)
		{
			// header 書き込み
			mb_convert_variables('SJIS', 'UTF-8', $header);
			fputcsv($stream, $header);

			// data 書き込み
			foreach($data as $d)
			{
				mb_convert_variables('SJIS', 'UTF-8', $d);
				fputcsv($stream, $d);
			}
		}

		// ファイルを閉じる
		fclose($stream);
		/*============================*/

		// 作成したcsvをダウンロード
		$this->download($path);
	}
	/*============================================ end function ==*/

	/* download file ---------------------------------------------*/
	function download($pPath, $pMimeType = null)
	{
		//-- ファイルが読めない時はエラー(もっときちんと書いた方が良いが今回は割愛)
		if (!is_readable($pPath)) { die($pPath); }

		//-- Content-Typeとして送信するMIMEタイプ(第2引数を渡さない場合は自動判定) ※詳細は後述
		$mimeType = 'text/csv';

		//-- 適切なMIMEタイプが得られない時は、未知のファイルを示すapplication/octet-streamとする
		if (!preg_match('/\A\S+?\/\S+/', $mimeType)) {
			$mimeType = 'application/octet-stream';
		}

		//-- Content-Type
		header('Content-Type: ' . $mimeType);

		//-- ウェブブラウザが独自にMIMEタイプを判断する処理を抑止する
		header('X-Content-Type-Options: nosniff');

		//-- ダウンロードファイルのサイズ
		header('Content-Length: ' . filesize($pPath));

		//-- ダウンロード時のファイル名
		header('Content-Disposition: attachment; filename="' . basename($pPath) . '"');

		//-- keep-aliveを無効にする
		header('Connection: close');

		//-- readfile()の前に出力バッファリングを無効化する ※詳細は後述
		while (ob_get_level()) { ob_end_clean(); }

		//-- 出力
		readfile($pPath);

		//-- 最後に終了させるのを忘れない
		exit;
	}
	/*============================================ end function ==*/

	/* get csv data ----------------------------------------------*/
	private function getExportData($department_id, $date)
	{
		$month = new Carbon($date);
		// 部署選択で全てを選択されている場合
		if($department_id === 0)
		{
			$users = User::whereNull('leaving')->get();
		}
		else
		{
			$users = User::where('department_id', $department_id)->whereNull('leaving')->get();
		}
		$data = [];

		foreach($users as $user)
		{
			// パラメーターの初期化、設定
			$param = [
				'user_id' => $user->id,
				'user_name' => $user->name,
				'work_days' => 0,
				'work_time' => new Carbon(0, 0, 0),
				'over_time' => new Carbon(0, 0, 0),
				'paid_days' => 0,
			];
			
			$work_times = WorkTime::whereMonth('date', $month->month)
							->where('user_id', $user->id)
							->get();
			foreach($work_times as $work_time)
			{
				if(	$work_time->work_type_id === 1 ||	// 出勤
					$work_time->work_type_id === 3 ||	// 遅刻
					$work_time->work_type_id === 4 ||	// 早退
					$work_time->work_type_id === 5)		// 遅刻 && 早退
				{
					// 出勤日数加算
					$param['work_days']++;

					// 労働時間加算
					$calc = $this->calcWorkTime($work_time);
					$param['work_time']->addHours($calc->hour)->addMinute($calc->minute);

					// 時間外労働時間加算
					$over = new Carbon($work_time->over_time);
					$param['over_time']->addHours($over->hour)->addMinute($over->minute);
				}
				else if($work_time->work_type_id === 6)	// 有給休暇
				{
					// 出勤日数加算
					$param['paid_days']++;
				}
			}

			$param['work_time'] = $param['work_time']->format('H:i');
			$param['over_time'] = $param['over_time']->format('H:i');
			array_push($data, $param);
		}

		return $data;
	}
	/*============================================ end function ==*/

	/* calc working hours ----------------------------------------*/
	private function calcWorkTime($work_time)
	{
		// 就業規定時間
		$fixed = FixedTime::first();
		$fixed_start = new Carbon($fixed->start_time);
		$fixed_left = new Carbon($fixed->left_time);

		// 就業時間
		$start = new Carbon($work_time->start_time);
		$left = new Carbon($work_time->left_time);
		$rest = new Carbon($work_time->rest_time);
		$over = new Carbon($work_time->over_time);


		// 丸め誤差
		switch($work_time->work_type_id)
		{
		case 1:	// 出勤
		case 4:	// 早退
			$start->hour = $fixed_start->hour;
			$start->minute = $fixed_start->minute;

			// 早退の場合
			if($work_time->work_type_id === 4) { break; }
			// 出勤の場合
			// break;

//		case 1: // 出勤
		case 3:	// 遅刻
			// 時間外労働していた場合
			$no_over = $over->copy()->setTime(0, 0, 0);
			if($over != $no_over) { break; }

			$left->hour = $fixed_left->hour;
			$left->minute = $fixed_left->minute;
		}


		// 終業時間 - 始業時間 - 休憩時間 + 時間外
		$work = $left->copy()->subHour($start->hour)->subMinutes($start->minute)
							->subHour($rest->hour)->subMinutes($rest->minute)
							->addHour($over->hour)->addMinutes($over->minute);
		return $work;
	}
	/*============================================ end function ==*/
}
