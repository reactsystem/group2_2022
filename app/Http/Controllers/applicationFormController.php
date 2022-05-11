<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ApplicationType;
use App\Models\FixedTime;
use Carbon\Carbon;

class ApplicationFormController extends Controller
{
    public function index(){

        return view('application.index');
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
    public function create(Request $request, $user){

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
