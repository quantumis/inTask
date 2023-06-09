<?php

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

/*User Routes*/
Route::get('/users', [App\Http\Controllers\UserController::class, 'getAllUsers']);
Route::post('/users', [App\Http\Controllers\UserController::class, 'createUser']);
Route::get('/users/{id}', [App\Http\Controllers\UserController::class, 'getUser']);
Route::post('/users/email/edit', [App\Http\Controllers\UserController::class, 'editMail']);
Route::post('/users/one/password/edit', [App\Http\Controllers\UserController::class, 'editPass']);
Route::post('/users/name/edit', [App\Http\Controllers\UserController::class, 'editFirstLastName']);
/*----------*/
/*Tast Routes*/
Route::post('/tasks', [App\Http\Controllers\TaskController::class, 'createTask']);
Route::get('/tasks/{boards}/{id}', [App\Http\Controllers\TaskController::class, 'getUserBoardTask']);
Route::get('/tasks/{id_user}', [App\Http\Controllers\TaskController::class, 'getAllTask']);
Route::delete('/tasks/{id}', [App\Http\Controllers\TaskController::class, 'deleteTask']);
Route::post('/tasks/{id}', [App\Http\Controllers\TaskController::class, 'completeTask']);
Route::get('/tasks/complete', [App\Http\Controllers\TaskController::class, 'getCompleteTask']);
Route::get('/tasks/user/today/{id_user}', [App\Http\Controllers\TaskController::class, 'getTodayTask']);
/*-----------*/
/*Board Routes*/
Route::post('/boards', [App\Http\Controllers\BoardController::class, 'createBoard']);
Route::get('/boards/{id}', [App\Http\Controllers\BoardController::class, 'getUserBoards']);
Route::get('/boards/{id}/count', [App\Http\Controllers\BoardController::class, 'getCountTaskInBoards']);
/*------------*/
/*SubTask*/
Route::post('/subtask', [App\Http\Controllers\SubTaskController::class, 'createSubTask']);
/*-------*/
