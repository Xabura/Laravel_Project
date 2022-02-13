<?php

use App\Http\Controllers\CovidController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/countries/list', [CovidController::class, 'list'])->middleware('auth:sanctum');
Route::get('/countries', [CovidController::class, 'countries'])->middleware('auth:sanctum');
Route::post('/add-country', [CovidController::class, 'addCountry'])->middleware('auth:sanctum');
Route::delete('/remove-country/{id}', [CovidController::class, 'removeCountry'])->middleware('auth:sanctum');

