<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkTime;
use App\Models\WorkType;
use App\Models\FixedTime;
use App\Models\PaidLeave;
use Carbon\Carbon;
use Yasumi\Yasumi;

class PersonalMgmtController extends Controller
{
    public function index(Request $request) {
        $items = $request->old();
        // 当日の日付表示と月表示を取得する
        Carbon::setLocale('ja');
        $today = Carbon::createFromDate();
        $month = Carbon::createFromDate();

        // 年間の祝日を取得する
        $now = now();
        $holidays = Yasumi::create('Japan', $now->year, 'ja_JP');

        // 当日の打刻メモが存在すれば取得する
        $date = date("Y-m-d");
        $description = DB::table('work_times')->select('description')->where('date', $date)->first();
        
        // 今月の最初の日付を取得する
        $dt = Carbon::createFromDate();
        $dt->startOfMonth(); //今月の最初の日
        $dt->timezone = 'Asia/Tokyo'; //日本時刻で表示
        $daysInMonth = $dt->daysInMonth; //今月は何日まであるか

        $user = Auth::user();
        $current_month = date("Y-m");
        $work_times = WorkTime::where('user_id', $user->id)->where('date', 'like', $current_month . '%')->get();
        $fixed_time = FixedTime::first();
        $paid_leaves = PaidLeave::where('user_id', $user->id)->first();

        return view('manager.personal_mgmt', [
            'today' => $today,
            'month' => $month,
            'dt' => $dt,
            'daysInMonth' => $daysInMonth,
            'work_times' => $work_times,
            'fixed_time' => $fixed_time,
            'paid_leaves' => $paid_leaves,
            'user' => $user,
            'description' => $description,
            'holidays' => $holidays,
            'items' => $items,
        ]);
    }

    public function update(Request $request) {
        $items = $request->all();
        $count = count($items['date']);

        for ($i = 0; $i < $count; $i++)
        {
            if ($items['work_type'][$i] !== NULL) {
                if (WorkTime::where('user_id', $items['user_id'])->where('date', $items['date'][$i])->exists())
                {
                    WorkTime::where('user_id', $items['user_id'])
                        ->where('date', $items['date'][$i])
                        ->update([
                            'work_type_id' => $items['work_type'][$i],
                            'start_time' => $items['start_time'][$i],
                            'left_time' => $items['left_time'][$i],
                    ]);
                } else {
                    $work_time = new WorkTime;
                    $work_time->date = $items['date'][$i];
                    $work_time->user_id = $items['user_id'][$i];
                    $work_time->work_type_id = $items['work_type'][$i];
                    $work_time->start_time = $items['start_time'][$i];
                    $work_time->left_time = $items['left_time'][$i];
                    $work_time->save();
                }
            }
        }
        return redirect('personal_management')->with('message', '勤務表を更新しました');
    }
}
