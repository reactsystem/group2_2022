<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use App\Models\FixedTime;
use App\Models\User;
use App\Models\WorkTime;

class DepartmentMgmtController extends Controller
{
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
			$dept = Department::where('id', $request->department)->first();
		}

		// 各種テーブルの取得
		$departments = Department::all();
		$fixed_time = FixedTime::first();
		$work_times = WorkTime::where('date', $date->toDateString())
			->whereHas('user', function($query) use($dept)
				{ $query->where('department_id', $dept->id); })
			->paginate($request->disp_limit);

		$param = [
			'date' => $date,
			'dept' => $dept,
			'departments' => $departments,
			'fixed_time' => $fixed_time,
			'work_times' => $work_times,
			'disp_limit' => $request->disp_limit,
		];
		return view('manager.department_mgmt', $param);
	}

	public function workTimeExport(int $department_id, Carbon $date)
	{
		$user = User::where('department_id', $department_id)->get();
		
	}
}