<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\EmployeesFormController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\InputFormController;
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
Route::post('/select_month', [InputFormController::class, 'test'])->middleware('auth');

//社員管理フォーム
Route::group(['prefix' => 'employees', 'as' => 'employees.',], function(){
    Route::get('/', [EmployeesFormController::class, 'show'])->name('show');
    Route::get('/create', [EmployeesFormController::class, 'create'])->name('create');
    Route::get('/edit', [EmployeesFormController::class, 'edit'])->name('edit');
});

// 個人勤怠管理フォーム
Route::get('/personal_management', function () {
    return view('personal_management');
})->name('mgmt.personal');

//各種申請フォーム
Route::get('/application-form', [ApplicationFormController::class, 'index'])->name('app.index');

//申請承認フォーム
Route::get('/approval-form', [ApplicationFormController::class, 'approve'])->name('app.approval');

//申請一覧フォーム
Route::get('/show-form', [ApplicationFormController::class, 'show'])->name('app.show');

// 部署勤怠管理フォーム
Route::get('/management', [ManagerController::class, 'index'])->name('mgmt.dept');

// マスタ管理フォーム
Route::get('/management/master', [ManagerController::class, 'getMaster'])->name('mgmt.master');
