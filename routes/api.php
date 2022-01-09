<?php

namespace App\Http\Controllers;

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



Route::post('/create-user', [UserController::class, 'createUser']);
Route::post('/login', [UserController::class, 'authenticate']);
// Route::post('login', 'UserController@authenticate');
// Route::get('open', 'DataController@open');
//
// Route::group(['middleware' => ['jwt.verify']], function() {
//    Route::get('user', 'UserController@getAuthenticatedUser');
//    Route::get('closed', 'DataController@closed');
// });
