<?php

date_default_timezone_set('Asia/Manila');

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\APIController;

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

Route::get('/stocks', function() {
    return view('modules.stocks');
});

Route::get('/sales', function() {
    return view('modules.sales');
});

// API
Route::get('/api/stockslist',[APIController::class,'stocksList']);

Route::post('/api/updatePrice',[APIController::class,'updatePrice']);
Route::post('/api/updateStocks',[APIController::class,'updateStocks']);
Route::post('/api/transactSales',[APIController::class,'transactSales']);