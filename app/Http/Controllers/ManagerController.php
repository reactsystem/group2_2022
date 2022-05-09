<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ApplicationType;
use App\Models\Department;
use App\Models\FixedTime;
use App\Models\WorkTime;
use App\Models\WorkType;

class ManagerController extends Controller
{
	public function index(Request $request)
	{
		$date = Carbon::now()->toDateString();
		if(isset($request->date))
		{
			$date = $request->date;
		}

		$param = [
			'date' => $date,
		];
		return view('manager.dept_attendance_mgmt', $param);
	}
	
	public function getMaster(Request $request)
	{
		return view('manager.master_mgmt');
	}
}
