<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::get('/users',[UserController::class,'index'])->name('user.index');
Route::post('/users/store',[UserController::class,'store'])->name('user.store');
Route::put('/users/update/{id}',[UserController::class,'update'])->name('user.update');
Route::delete('/users/destroy/{id}',[UserController::class,'destroy'])->name('user.destroy');
