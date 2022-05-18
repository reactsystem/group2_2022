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
Route::post('/', [InputFormController::class, 'add'])->name('input')->middleware('auth');
// Route::post('/select_month', [InputFormController::class, 'selectMonth'])->middleware('auth');

//社員管理フォーム
Route::group(['prefix' => 'employees', 'as' => 'employees.', 'middleware' => 'auth'], function(){
    Route::get('/', [EmployeesFormController::class, 'show'])->name('show');
    Route::get('/add', [EmployeesFormController::class, 'add'])->name('add');
    Route::post('/add', [EmployeesFormController::class, 'create'])->name('create');
    Route::get('/edit/{user}', [EmployeesFormController::class, 'edit'])->name('edit');
    Route::post('/edit{user}', [EmployeesFormController::class, 'update'])->name('update');
});

// 個人勤怠管理フォーム
Route::get('/personal_management', [PersonalMgmtController::class, 'index'])->name('mgmt.personal');
Route::post('/personal_management', [PersonalMgmtController::class, 'update'])->name('update');

//申請フォーム
Route::group(['prefix' => 'application', 'as' => 'application.', 'middleware' => 'auth'], function(){
    Route::get('/', [ApplicationFormController::class, 'index'])->name('index');
    Route::get('/form', [ApplicationFormController::class, 'show'])->name('show');
    Route::post('/form/{user}', [ApplicationFormController::class, 'create'])->name('create');
    Route::get('/approval/{user}/{application}', [ApplicationFormController::class, 'approve'])->name('approve');
    Route::post('/mail', [ApplicationFormController::class,'send']);
});

// 部署勤怠管理フォーム
Route::get('/management', [DepartmentMgmtController::class, 'index'])->name('mgmt.dept');
Route::post('/management', [DepartmentMgmtController::class, 'index'])->name('mgmt.dept.post');

// マスタ管理フォーム
Route::group(['prefix' => 'master', 'as' => 'master.'], function(){
	Route::get('/', [MasterController::class, 'show'])->name('show');
	Route::post('/add', [MasterController::class, 'create'])->name('create');
	Route::post('/edit', [MasterController::class, 'update'])->name('update');
	Route::post('/delete', [MasterController::class, 'delete'])->name('delete');
});
