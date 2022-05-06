<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class applicationFormController extends Controller
{
    public function index(){
        return view('application.form');
    }

    public function approve(){
        return view('application.approval_form');
    }
}
