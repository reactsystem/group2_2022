<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ApplicationType;
use App\Models\FixedTime;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Requests\ApplicationFormRequest;
use Illuminate\Support\Facades\Validator;
use App\Mail\SendMail;
use App\Models\PaidLeave;
use App\Models\WorkTime;
use Illuminate\Support\Facades\Mail;

class ApplicationFormController extends Controller
{
	/* 申請一覧フォーム ------------------------------------------*/
    public function index(Request $request){
        $loginUser = Auth::user()->department_id;
        $loginUserDepartment = Department::where('id', $loginUser)->first()->name;
        $departments = Department::whereNull('deleted_at')->get();
        $applications = '';
        
        // 部署ごとに表示、statusが0のデータのみ表示
        if($request->query('department')){
            $applications = Application::whereIn('user_id', function ($query) use ($request) {
                $query->from('users')
                ->select('id')
                ->where('department_id', $request->department);
            })->where('status', 0)->paginate();
        }else{
            $applications = Application::whereIn('user_id', function ($query){
                $query->from('users')
                ->select('id');
            })->where('status', 0)->paginate();
        }

        // 表示件数
        $limit_disp = ['全て', '5件', '10件', '20件'];

        // 部署が全て、表示件数で絞る
        if(!$request->query('disp_limit')){
            $applications = Application::whereIn('user_id', function ($query) use ($request){
                $query->from('users')
                ->select('id');
            })->where('status', 0)->paginate(100);
        }elseif($request->query('disp_limit')==='1'){
            $applications = Application::whereIn('user_id', function ($query) use ($request){
                $query->from('users')
                ->select('id');
            })->where('status', 0)->paginate(5);
        }elseif($request->query('disp_limit')==='2'){
            $applications = Application::whereIn('user_id', function ($query) use ($request){
                $query->from('users')
                ->select('id');
            })->where('status', 0)->paginate(10);
        }elseif($request->query('disp_limit')==='3'){
            $applications = Application::whereIn('user_id', function ($query) use ($request){
                $query->from('users')
                ->select('id');
            })->where('status', 0)->paginate(20);
        }
        
        //部署ごとに表示＋表示件数、statusが0のデータのみ表示
        if($request->query('department')){
            if(!$request->query('disp_limit')){
                $applications = Application::whereIn('user_id', function ($query) use ($request){
                    $query->from('users')
                    ->select('id')
                    ->where('department_id', $request->department);
                })->where('status', 0)->paginate(100);
            }elseif($request->query('disp_limit')==='1'){
                $applications = Application::whereIn('user_id', function ($query) use ($request){
                    $query->from('users')
                    ->select('id')
                    ->where('department_id', $request->department);
                })->where('status', 0)->paginate(5);
            }elseif($request->query('disp_limit')==='2'){
                $applications = Application::whereIn('user_id', function ($query) use ($request){
                    $query->from('users')
                    ->select('id')
                    ->where('department_id', $request->department);
                })->where('status', 0)->paginate(10);
            }elseif($request->query('disp_limit')==='3'){
                $applications = Application::whereIn('user_id', function ($query) use ($request){
                    $query->from('users')
                    ->select('id')
                    ->where('department_id', $request->department);
                })->where('status', 0)->paginate(20);
            }
        }

        return view('application.index', compact('loginUser', 'loginUserDepartment', 'departments', 'applications', 'limit_disp'));
    }
	/*============================================ end function ==*/

	/* 申請フォーム ----------------------------------------------*/
    public function show(Request $request){
        $user = Auth::user();
        $types = ApplicationType::whereNull('deleted_at')->get();
        $time = FixedTime::first();

		// 有給残り日数
		$paid = PaidLeave::where('user_id', $user->id)
			->where('expire_date', '>=', date('Y-m-d'))
			->get();
		$app_paid = Application::where('user_id', $user->id)
			->where('application_type_id', 1)
			->where('date', '>', date('Y-m-d'))
			->count();
		$left_days = 0;
		foreach($paid as $days)
		{
			$left_days += $days->left_days;
		}
		$left_days -= $app_paid;

        // 開始時間
        $left_time = new Carbon($time->left_time);
        $left_time->addMinutes(15);
        $left_time = $left_time->toTimeString('minute');

        // getパラメータ(申請日)
        $param = $request->query('date');
        
        // 申請者の打刻時間(区分が出勤、遅刻、早退、遅刻/早退のみ)
        $work_time = WorkTime::whereIn('work_type_id', [1,3,4,5])->where('user_id', $user->id)->where('date', $param)->first();

        return view('application.form', compact('user', 'types', 'left_time', 'left_days', 'work_time', 'param' ));
    }
	/*============================================ end function ==*/

	/* 申請フォームの内容をApplicationテーブルに格納 -------------*/
    public function create(ApplicationFormRequest $request, $user){

        Application::insert([
            'user_id' => $user,
            'application_type_id' => $request->appliedContent,
            'reason' => $request->reason,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 0
        ]);

        return redirect('/')->with('sent_form', '申請書が送信されました');
    }
	/*============================================ end function ==*/

	/* 申請承認 --------------------------------------------------*/
    public function send(Request $request) {
        $user = Auth::user();
        $fixed_time = FixedTime::first();

        // 承認結果によってApplicationテーブルのstatusカラムを更新
        $application = Application::find($request->id);
        if ($request->result === '承認') {
            $application->status = 1;

            // 申請種別が有給休暇、特別休暇の場合、work_timeテーブルの申請対象日の勤務区分を更新
            if ($application->application_type_id == 1 or $application->application_type_id == 2) {
                if (WorkTime::where('user_id', $application->user_id)->where('date', $application->date)->exists()) {
                    $work_time = WorkTime::where('user_id', $application->user_id)->where('date', $application->date)->first();
                    $work_time->work_type_id = $application->applicationType->work_type_id;
                    $work_time->start_time = NULL;
                    $work_time->left_time = NULL;
                    $work_time->rest_time = NULL;
                    $work_time->save();
                } else {
                    $work_time = new WorkTime;
                    $work_time->user_id = $application->user_id;
                    $work_time->work_type_id = $application->applicationType->work_type_id;
                    $work_time->date = $application->date;
                    $work_time->save();
                }
            }

            // 有給休暇の場合、申請者の残り有給数を減らす
			// 未来の日付の有給休暇消費処理はexpend_paid_leaves.phpで実行
            // 過去の日付(当日含む)の有休申請は以下で残り有給数を減らす
            if ($application->application_type_id == 1 && $application->date <= date('Y-m-d')) {
                $paid_leave = PaidLeave::where('user_id', $application->user_id)->where('left_days', '>', '0')->oldest('expire_date')->first();
                $paid_leave->left_days --;
                $paid_leave->updated_at = date('Y-m-d H:i:s');
                $paid_leave->save();
            }

            // 申請種別が打刻時間修正の場合、work_timeテーブルの申請対象日の開始時間、終了時間を更新
            if ($application->application_type_id == 5) {
                if (WorkTime::where('user_id', $application->user_id)->where('date', $application->date)->exists()) {
                    $work_time = WorkTime::where('user_id', $application->user_id)->where('date', $application->date)->first();
                } else {
                    $work_time = new WorkTime;
                    $work_time->user_id = $application->user_id;
                    $work_time->date = $application->date;
                }

                $work_time->start_time = $application->start_time;

                // 「出勤」か「遅刻」かを判定して勤務区分を更新
                if ($application->start_time <= $fixed_time->start_time) {
                    $work_time->work_type_id = 1;
                } else {
                    $work_time->work_type_id = 3;
                }

                // 終了時間が入力されていた場合、終了時間、休憩時間、時間外勤務の処理を実行
                if(isset($application->end_time)) {
                    $work_time->left_time = $application->end_time;

                    // 「早退」か「遅刻/早退」可を判定して勤務区分を更新
                    if ($application->end_time < $fixed_time->left_time) {
                        // 既に「遅刻」だった場合は「遅刻/早退」
                        if ($work_time->work_type_id == 3) {
                            $work_time->work_type_id = 5;
                        // そうでない場合は「早退」
                        } else {
                            $work_time->work_type_id = 4;
                        }
                    }

                    // 勤務時間から差し引く既定の休憩時間を取得
                    $from = strtotime('00:00:00');
                    $end = strtotime($fixed_time->rest_time);
                    $minutes = ($end - $from) / 60;
                    $calculate_rest = "-" . $minutes . "min";
                    
                    // 実労働時間(勤務時間 - 休憩時間)を分で取得
                    // 規定時刻より早く出社した場合
                    if ($work_time->start_time < $fixed_time->start_time) {
                        $worked_time = (strtotime($work_time->left_time) - strtotime($fixed_time->start_time));
                        $worked_time = strtotime($calculate_rest, $worked_time) / 60;
                    // 規定時刻より後に出社した場合
                    } else {
                        $worked_time = (strtotime($work_time->left_time) - strtotime($work_time->start_time));
                        $worked_time = strtotime($calculate_rest, $worked_time) / 60;
                    }

                    // 実労働時間が６時間に満たない場合は、休憩時間に「00:00:00」を追加
                    if ($worked_time < 360) {
                        $work_time->rest_time = '00:00:00';
                    // 実労働時間が８時間を超える場合で、かつ既定の休憩時間が１時間未満の場合、休憩時間を「01:00:00」にする
                    } elseif ($worked_time >= 480 && $fixed_time->rest_time < '01:00:00') {
                        $work_time->rest_time = '01:00:00';
                    } else {
                        $work_time->rest_time = $fixed_time->rest_time;
                    }

                    // 時間外労働の処理
                    $fixed_left_over = strtotime($fixed_time->getRoundTime(), strtotime($fixed_time->left_time));
                    $left_time = strtotime($application->end_time);
                    if ($left_time >= $fixed_left_over) {
                        $over_time = $left_time - $fixed_left_over;
                        $over_time = gmdate("H:i", $over_time);
                        $work_time->over_time = $over_time;
                    } else {
                        $work_time->over_time = '00:00:00';
                    }
                } else {
                    $work_time->left_time = NULL;
                }
                $work_time->save();
            }

        } elseif ($request->result === '却下') {
            $application->status = 2;
        } elseif ($request->result === '取り下げ') {
            $application->status = 3;
		}
        $application->save();

        // 申請結果通知メールの送信処理
        $data = [
            'user' => $user->name,
            'name' => $request->name,
            'result' => $request->result,
            'applied_content' => $request->applied_content,
            'reason' => $request->reason,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'comment' => $request->comment,
        ];
        Mail::to('admin@hoge.co.jp')->send(new SendMail($data));

        return redirect()->route('application.index', ['department' => $request->department_id])->with('message', '申請結果を通知しました');
    }
	/*============================================ end function ==*/
}
