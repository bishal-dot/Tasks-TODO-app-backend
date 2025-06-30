<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// register
Route::post('/user/register', [AuthController::class, 'register']);
// login
Route::post('/user/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum']], function(){

    Route::get('/user/logout', [AuthController::class, 'logout']);

    Route::get('/user/tasks',[TasksController::class,'show']);

    Route::post('/task/addtask', [TasksController::class,'add']);

    Route::post('/task/edit/{id}', [TasksController::class,'edit']);

    Route::delete('/task/remove/{id}', [TasksController::class,'remove']);

    Route::get('/task/search/{keyword}',[TasksController::class,'search']);

    Route::get('/task/complete/{id}',[TasksController::class,'complete']);

});


