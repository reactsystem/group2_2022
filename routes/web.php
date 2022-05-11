<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\EmployeesFormController;
use App\Http\Controllers\ManagerController;
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

Auth::routes();

// 勤怠入力フォーム
Route::get('/', function () {
    return view('input');
})->name('input')->middleware('auth');

//社員管理フォーム
Route::group(['prefix' => 'employees', 'as' => 'employees.', 'middleware' => 'auth'], function(){
    Route::get('/', [EmployeesFormController::class, 'show'])->name('show');
    Route::get('/create', [EmployeesFormController::class, 'create'])->name('create');
    Route::get('/edit', [EmployeesFormController::class, 'edit'])->name('edit');
});

// 個人勤怠管理フォーム
Route::get('/personal_management', function () {
    return view('personal_management');
})->name('mgmt.personal');

//申請フォーム
Route::group(['prefix' => 'application', 'as' => 'application.', 'middleware' => 'auth'], function(){
    Route::get('/', [ApplicationFormController::class, 'index'])->name('index');
    Route::get('/form', [ApplicationFormController::class, 'show'])->name('show');
    Route::post('/form/{user}', [ApplicationFormController::class, 'create'])->name('create');
});


//申請承認フォーム
Route::get('/approval-form', [ApplicationFormController::class, 'approve'])->name('app.approval');

//申請一覧フォーム
Route::get('/show-form', [ApplicationFormController::class, 'show'])->name('app.show');

// 部署勤怠管理フォーム
Route::get('/management', [ManagerController::class, 'index'])->name('mgmt.dept');
Route::post('/management', [ManagerController::class, 'index'])->name('mgmt.dept.p');

// マスタ管理フォーム
Route::get('/management/master', [ManagerController::class, 'getMaster'])->name('mgmt.master');
