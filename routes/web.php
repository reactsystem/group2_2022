<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\EmployeesFormController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('input', function () {
    return view('input');
});

Auth::routes();

//社員管理フォーム
Route::group(['prefix' => 'employees', 'as' => 'employees.',], function(){
    Route::get('/', [EmployeesFormController::class, 'show'])->name('show');
    Route::get('/create', [EmployeesFormController::class, 'create'])->name('create');
    Route::get('/edit', [EmployeesFormController::class, 'edit'])->name('edit');
});

Route::get('/personal_management', function () {
    return view('personal_management');
});

//各種申請フォーム
Route::get('/application-form', [ApplicationFormController::class, 'index']);

//申請承認フォーム
Route::get('/approval-form', [ApplicationFormController::class, 'approve']);

//申請一覧フォーム
Route::get('/show-form', [ApplicationFormController::class, 'show']);