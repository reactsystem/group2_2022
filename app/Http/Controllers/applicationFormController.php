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

class ApplicationFormController extends Controller
{
    // 申請一覧フォーム
    public function index(Request $request){

        $loginUser = Auth::user()->department_id;
        $loginUserDepartment = Department::where('id', $loginUser)->first()->name;
        $departments = Department::get();
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

        //部署ごとに表示＋表示件数、statusが0のデータのみ表示
        if($request->query('disp_limit') && $request->query('department')){
            if($request->query('disp_limit')==='0'){
                $applications = Application::whereIn('user_id', function ($query) use ($request){
                    $query->from('users')
                    ->select('id')
                    ->where('department_id', $request->department);
                })->where('status', 0)->paginate();
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

    public function approve(){
        return view('application.approval_form');
    }


}
