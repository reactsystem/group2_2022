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
use App\Models\WorkTime;
use Illuminate\Support\Facades\Mail;

class ApplicationFormController extends Controller
{
    // 申請一覧フォーム
    public function index(Request $request){

        $loginUser = Auth::user()->department_id;
        $loginUserDepartment = Department::where('id', $loginUser)->first()->name;
        $departments = Department::get();
        $applications = '';
        
        // dd($application);
        // 部署ごとに表示
        if($request->query('department')){
            $applications = Application::whereIn('user_id', function ($query) use ($request) {
                $query->from('users')
                ->select('id')
                ->where('department_id', $request->department);
            })->paginate();
        }else{
            $applications = Application::whereIn('user_id', function ($query){
                $query->from('users')
                ->select('id');
            })->paginate();
        }

        // 表示件数
        $limit_disp = ['全て', '5件', '10件', '20件'];

        //部署ごとに表示＋表示件数
        if($request->query('disp_limit') && $request->query('department')){
            if($request->query('disp_limit')==='0'){
                $applications = Application::whereIn('user_id', function ($query) use ($request){
                    $query->from('users')
                    ->select('id')
                    ->where('department_id', $request->department);
                })->paginate();
            }elseif($request->query('disp_limit')==='1'){
                $applications = Application::whereIn('user_id', function ($query) use ($request){
                    $query->from('users')
                    ->select('id')
                    ->where('department_id', $request->department);
                })->paginate(5);
            }elseif($request->query('disp_limit')==='2'){
                $applications = Application::whereIn('user_id', function ($query) use ($request){
                    $query->from('users')
                    ->select('id')
                    ->where('department_id', $request->department);
                })->paginate(10);
            }elseif($request->query('disp_limit')==='3'){
                $applications = Application::whereIn('user_id', function ($query) use ($request){
                    $query->from('users')
                    ->select('id')
                    ->where('department_id', $request->department);
                })->paginate(20);
            }
        }

        return view('application.index', compact('loginUser', 'loginUserDepartment', 'departments', 'applications', 'limit_disp'));
    }

    // 申請フォーム
    public function show(){
        $user = Auth::user();
        $types = ApplicationType::get();
        $time = FixedTime::first();

        // 開始時間
        $left_time = new Carbon($time->left_time);
        $left_time->addMinutes(15);
        $left_time = $left_time->toTimeString('minute');

        return view('application.form', compact('user', 'types', 'left_time'));
    }

    // 申請フォームの内容をApplicationテーブルに格納
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

        return redirect('/')->with('sended_form', '申請書が送信されました');
    }

    public function approve(Request $request){
        $user = Auth::user();
        $application = Application::find($request->application);

        return view('application.approval_form', compact('user', 'application'));
    }

    public function send(Request $request) {
        $user = Auth::user();

        // 承認結果によってApplicationテーブルのstatusカラムを更新
        $application = Application::find($request->id);
        if ($request->result === '承認') {
            $application->status = 1;

            // 申請種別が有給休暇、特別休暇の場合、work_timeテーブルの申請対象日の勤務区分を更新
            if ($application->application_type_id == 1 or $application->application_type_id == 2) {
                if (WorkTime::where('user_id', $application->user_id)->where('date', $application->date)->exists()) {
                    $work_time = WorkTime::where('user_id', $application->user_id)->where('date', $application->date)->first();
                    $work_time->work_type_id = $application->applicationType->work_type_id;
                    $work_time->save();
                } else {
                    $work_time = new WorkTime;
                    $work_time->user_id = $application->user_id;
                    $work_time->work_type_id = $application->applicationType->work_type_id;
                    $work_time->date = $application->date;
                    $work_time->save();
                }
            }

            // 申請種別が打刻時間修正の場合、work_timeテーブルの申請対象日の開始時間、終了時間を更新
            if ($application->application_type_id == 5) {
                $work_time = WorkTime::where('user_id', $application->user_id)->where('date', $application->date)->first();
                $work_time->start_time = $application->start_time;
                $work_time->left_time = $application->end_time;
                $work_time->save();
            }

        } else if ($request->result === '差し戻し') {
            $application->status = 2;
        }
        $application->save();

        // 申請承認フォームのコメントに対するバリデーション
        $rules = [
            'comment' => 'max:60',
        ];

        $messages = [
            'comment.max' => 'コメントは６０文字以下で入力してください。',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

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

        return redirect('application/')->with('message', '申請結果を通知しました');
    }

}
