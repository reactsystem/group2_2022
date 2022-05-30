<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\DepartmentMgmtController;
use App\Http\Controllers\EmployeesFormController;
use App\Http\Controllers\InputFormController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\PersonalMgmtController;
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
Route::get('/', [InputFormController::class, 'show'])->name('input')->middleware('auth');
Route::post('/', [InputFormController::class, 'add'])->name('add')->middleware('auth');

//社員管理フォーム
Route::group(['prefix' => 'employees', 'as' => 'employees.', 'middleware' => 'manager'], function(){
    Route::get('/', [EmployeesFormController::class, 'show'])->name('show');
    Route::get('/add', [EmployeesFormController::class, 'add'])->name('add');
    Route::post('/add', [EmployeesFormController::class, 'create'])->name('create');
    Route::get('/edit/{user}', [EmployeesFormController::class, 'edit'])->name('edit');
    Route::post('/edit{user}', [EmployeesFormController::class, 'update'])->name('update');
});

// 個人勤怠管理フォーム
Route::get('/personal_management', [PersonalMgmtController::class, 'index'])->name('mgmt.personal')->middleware('manager');
Route::post('/personal_management', [PersonalMgmtController::class, 'update'])->name('update')->middleware('manager');

//申請フォーム
Route::group(['prefix' => 'application', 'as' => 'application.', 'middleware' => 'auth'], function(){
    Route::get('/index', [ApplicationFormController::class, 'indexSelf'])->name('indexSelf');
    Route::post('/index/stop', [ApplicationFormController::class, 'stop'])->name('stop');
    Route::get('/form', [ApplicationFormController::class, 'show'])->name('show');
    Route::post('/form/{user}', [ApplicationFormController::class, 'create'])->name('create');
});
Route::group(['prefix' => 'application', 'as' => 'application.', 'middleware' => 'manager'], function(){
    Route::get('/', [ApplicationFormController::class, 'index'])->name('index');
    Route::post('/mail', [ApplicationFormController::class,'send'])->name('approve');
});

// 部署勤怠管理フォーム
Route::group(['prefix' => 'management', 'as' => 'mgmt.', 'middleware' => 'manager'], function(){
	Route::get('/', [DepartmentMgmtController::class, 'index'])->name('dept');
	Route::post('/', [DepartmentMgmtController::class, 'index'])->name('dept.post');
	Route::post('/export', [DepartmentMgmtController::class, 'export'])->name('export');
});

// マスタ管理フォーム
Route::group(['prefix' => 'master', 'as' => 'master.', 'middleware' => 'manager'], function(){
	Route::get('/', [MasterController::class, 'show'])->name('show');
	Route::post('/add', [MasterController::class, 'create'])->name('create');
	Route::post('/edit', [MasterController::class, 'update'])->name('update');
	Route::post('/delete', [MasterController::class, 'delete'])->name('delete');
});
