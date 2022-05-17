<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ApplicationType;
use App\Models\Department;
use App\Models\FixedTime;
use App\Models\WorkType;
use Carbon\Carbon;

class MasterController extends Controller
{
	public function show(Request $request)
	{
		$fixed_time = FixedTime::first();
		$departments = Department::all();
		$work_types = WorkType::all();
		$app_types = ApplicationType::all();

		$param = [
			'fixed_time' => $fixed_time,
			'departments' => $departments,
			'work_types' => $work_types,
			'app_types' => $app_types,
		];
		return view('manager.master_mgmt', $param);
	}

	public function create(Request $request)
	{
		switch($request->table)
		{
			case 'application':
				ApplicationType::insert([
					'name' => $request->name,
					'work_type_id' => $request->work_type_id,
					'created_at' => Carbon::now(),
				]);
				break;
			case 'department':
				Department::insert([
					'name' => $request->name,
					'created_at' => Carbon::now(),
				]);
				break;
			case 'work_type':
				WorkType::insert([
					'name' => $request->name,
					'created_at' => Carbon::now(),
				]);
				break;
		}

		return redirect(route('master.show'));
	}

	public function update(Request $request)
	{
		switch($request->table)
		{
			case 'application':
				$edit_app = ApplicationType::find($request->id);
				$edit_app->name = $request->name;
				$edit_app->work_type_id = $request->work_type_id;
				$edit_app->updated_at = Carbon::now();
				$edit_app->save();
				break;
			case 'department':
				$edit_dept = Department::find($request->id);
				$edit_dept->name = $request->name;
				$edit_dept->updated_at = Carbon::now();
				$edit_dept->save();
				break;
			case 'fixed_time':
				$edit_dept = FixedTime::find($request->id);
				$edit_dept->start_time = $request->start_time;
				$edit_dept->left_time = $request->left_time;
				$edit_dept->rest_time = $request->rest_time;
				$edit_dept->updated_at = Carbon::now();
				$edit_dept->save();
				break;
			case 'work_type':
				$edit_type = WorkType::find($request->id);
				$edit_type->name = $request->name;
				$edit_type->updated_at = Carbon::now();
				$edit_type->save();
				break;
		}

		return redirect(route('master.show'));
	}

	public function delete(Request $request)
	{
		switch($request->table)
		{
			case 'application':
				ApplicationType::find($request->id)->delete();
				break;
			case 'department':
				Department::find($request->id)->delete();
				break;
			case 'work_type':
				WorkType::find($request->id)->delete();
				break;
		}

		return redirect(route('master.show'));
	}
}
