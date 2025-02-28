<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function (\Illuminate\Http\Request $request) {

    $params = $request->all();

    return view('home')->with('error',$params['error'] ?? '');

    return view('welcome');
})->name('home');

Route::post('/login', function (\Illuminate\Http\Request $request){
    $request->session()->put('username', $request->input('username'));
    $request->session()->put('password', $request->input('password'));
    return redirect('/dev');
});

Route::group([],function (){
    Route::get('/dev', function (){

        return view('operate');

    })->middleware([\App\Http\Middleware\UserCheck::class]);

    Route::get('/execute', [\App\Http\Controllers\Controller::class,'execute'])->middleware([\App\Http\Middleware\UserCheck::class]);

    Route::get('/export/excel', [\App\Http\Controllers\Controller::class,'exportExcel'])->name('export.excel')->middleware([\App\Http\Middleware\UserCheck::class]);

    Route::get('/export/json', [\App\Http\Controllers\Controller::class,'exportJson'])->name('export.json')->middleware([\App\Http\Middleware\UserCheck::class]);

});

