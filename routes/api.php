<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDetailsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [LoginController::class, 'login']);

Route::get('/user/index', [UserController::class, 'index']);
Route::post('/user/store', [UserController::class, 'store']);
Route::put('/user/update/{id}', [UserController::class, 'update']);
Route::delete('/user/delete/{id}', [UserController::class, 'destroy']);


Route::get('/userdetail/index', [UserDetailsController::class, 'index']);
Route::post('/userdetail/store', [UserDetailsController::class, 'store']);
Route::put('/userdetail/update/{id}', [UserDetailsController::class, 'update']);
Route::delete('/userdetail/delete/{id}', [UserDetailsController::class, 'destroy']);

Route::get('/todo/index', [TodoController::class, 'index']);
Route::post('/todo/store', [TodoController::class, 'store']);
Route::put('/todo/update/{id}', [TodoController::class, 'update']);
Route::delete('/todo/delete/{id}', [TodoController::class, 'destroy']);
