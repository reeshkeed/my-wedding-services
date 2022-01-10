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
//
Route::group(['middleware' => ['jwt.verify']], function() {
   Route::get('/guest', [UserController::class, 'getUserDetails']);
   Route::post('/guest/{id}', [UserController::class, 'updateResponse']);
});
