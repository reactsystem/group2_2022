<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class employeesFormController extends Controller
{

    //社員一覧ページ
    public function show(){
        
        return view('employees.show');
    }

    //社員追加ページ
    public function create(){

        return view('employees.create');
    }

    //社員編集ページ
    public function edit(){

        return view('employees.edit');
    }
}
