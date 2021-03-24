<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProxyController;
use App\Models\Proxy;

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
    return redirect('check');
});

Route::get('/check', [ProxyController::class, 'index'])->name('check');
Route::post('/check', [ProxyController::class, 'check']);

Route::get('/archive', [ProxyController::class, 'archive'])->name('archive');
Route::get('/result/{log_id}', [ProxyController::class, 'result'])->name('result');
