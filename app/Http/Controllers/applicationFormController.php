<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ApplicationType;
use App\Models\FixedTime;

class ApplicationFormController extends Controller
{
    public function index(){

        return view('application.index');
    }

    public function show(){
        $user = Auth::user();
        $types = ApplicationType::get();
        $time = FixedTime::first();

        return view('application.form', compact('user', 'types', 'time'));
    }

    public function create(Request $request){


        return view('application.show');
    }

    public function approve(){
        return view('application.approval_form');
    }


}
