<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Middleware\UserCheck;
use App\Http\Controllers\Controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function (Request $request) {

    $params = $request->all();

    return view('home')->with('error', $params['error'] ?? '');

})->name('home');

Route::post('/login', function (Request $request) {
    $request->session()->put('username', $request->input('username'));
    $request->session()->put('password', $request->input('password'));
    return redirect('/dev');
});

Route::get('/dev', function () {

    return view('operate');

})->middleware([UserCheck::class]);

Route::get('/execute', [Controller::class, 'execute'])->middleware([UserCheck::class]);

Route::get('/export/excel', [Controller::class, 'exportExcel'])->name('export.excel')->middleware([UserCheck::class]);

Route::get('/export/json', [Controller::class, 'exportJson'])->name('export.json')->middleware([UserCheck::class]);

