<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeCreateRequest;
use App\Http\Requests\EmployeeEditRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\PaidLeave;

class employeesFormController extends Controller
{

    // キーワード検索に%と_をエスケープ処理
    private function escape(string $value)
    {
        return str_replace(
            ['\\', '%', '_'],
            ['\\\\', '\\%', '\\_'],
            $value
        );
    }

    //社員一覧ページ
    public function show(Request $request)
    {
        $loginUser = Auth::user()->department_id;
        $loginUserDepartment = Department::where('id', $loginUser)->first()->name;
        $departments = Department::get();

        $query = User::query();
        
        // 部署検索
        if($request->department){
            $query->whereIn('department_id', function($query) use($request){
                $query->from('departments')
                ->select('id')
                ->where('department_id', $request->department);
            });
        }

        // キーワード検索
        if($request->keyword){
            $keyword = '%'. $this->escape($request->keyword). '%';
            $query->where(function($query) use ($keyword){
                $query->where('name', 'LIKE', $keyword);
                $query->orWhere('id', 'LIKE', $keyword);
            });
        }

        // 表示件数
        $limit_disp = ['全て', '5件', '10件', '20件', '50件', '100件'];

        //退職していない人のみ表示
        $users = $query->whereNull('leaving')->orderBy('joining', 'DESC')->paginate(100);
    
        //表示件数
        if($request->query('disp_limit') && $request->query('department')){
            if($request->query('disp_limit')==='0'){
                $users = $query->whereNull('leaving')->orderBy('joining', 'DESC')->paginate();
            }elseif($request->query('disp_limit')==='1'){
                $users = $query->whereNull('leaving')->orderBy('joining', 'DESC')->paginate(5);
            }elseif($request->query('disp_limit')==='2'){
                $users = $query->whereNull('leaving')->orderBy('joining', 'DESC')->paginate(10);
            }elseif($request->query('disp_limit')==='3'){
                $users = $query->whereNull('leaving')->orderBy('joining', 'DESC')->paginate(20);
            }elseif($request->query('disp_limit')==='4'){
                $users = $query->whereNull('leaving')->orderBy('joining', 'DESC')->paginate(50);
            }elseif($request->query('disp_limit')==='5'){
                $users = $query->whereNull('leaving')->orderBy('joining', 'DESC')->paginate(100);
            }
        }

        return view('employees.show', compact('loginUser', 'loginUserDepartment', 'departments', 'limit_disp', 'users'));
    }

    //社員追加ページ
    public function add()
    {
        $departments = Department::get();

        return view('employees.add', compact('departments'));
    }

    //社員追加
    public function create(EmployeeCreateRequest $request)
    {
        User::insert([
            'id' => $request->id,
            'name' => $request->name,
            'department_id' => $request->department,
            'email' => $request->email,
            'joining' => $request->joining,
            'password' => $request->password,
            'manager' => (boolean)$request->authority,
            'created_at' => Carbon::now()
        ]);

        PaidLeave::insert([
            'user_id' => $request->id,
        ]);
        PaidLeave::insert([
            'user_id' => $request->id,
        ]);

        return redirect()->route('employees.show',['department' => $request->department])->with(['message' => '社員番号：'.$request->id.'、社員名：'.$request->name.'さんを新規追加しました。']);
    }

    //社員編集ページ
    public function edit($user)
    {
        $editUser = User::find($user);
        $departments = Department::get();

        return view('employees.edit', compact('editUser', 'departments'));
    }

    //社員情報更新
    public function update(EmployeeEditRequest $request, $user)
    {
        $editUser = User::find($user);
        $editUser->name = $request->name;
        $editUser->department_id = $request->department;
        $editUser->email = $request->email;
        if($request->leaving){
            $editUser->leaving = $request->leaving;
        }
        $editUser->manager = (boolean)$request->authority;
        $editUser->updated_at = Carbon::now();
        $editUser->save();

        return redirect()->route('employees.show',['department' => $editUser->department_id])->with(['message' => '社員番号：'.$editUser->id.'、社員名：'.$request->name.'さんを更新しました。']);
    }
}
