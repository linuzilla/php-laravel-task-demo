<?php

use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\PortalLoginController;
use App\Http\Controllers\SubmitController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskStatusController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', fn() => view('index'))->name('root');

Route::get('dashboard', fn() => view('dashboard'))->middleware('auth');

Route::get('welcome', fn() => view('welcome'));

Route::get('bootswatch', fn() => view('bootswatch'));

Route::middleware(['auth'])->group(function () {
    Route::get('form', fn() => view('form'));
    Route::get('form/application', [ApplicationFormController::class, 'show'])->name('application.show');
    Route::patch('form/application', [ApplicationFormController::class, 'update'])->name('application.update');
    Route::delete('form/application', [ApplicationFormController::class, 'clear'])->name('application.clear');
    Route::post('form/application', [ApplicationFormController::class, 'run'])->name('application.run');

    Route::get('task/status', [TaskController::class, 'status'])->name('task.status');
    Route::get('task/result', [TaskController::class, 'result'])->name('task.result');
    Route::post('task/run', [TaskController::class, 'run'])->name('task.run');
    Route::post('task/start-over', [TaskController::class, 'startOver'])->name('task.run');
});


Route::get('login', [PortalLoginController::class, 'redirectToProvider'])->name('login');
Route::get('logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');
Route::get('callback', [PortalLoginController::class, 'handleProviderCallback']);

