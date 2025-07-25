<?php

use App\Http\Controllers\BonusSQL;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return 'hi';
})->middleware('auth:sanctum');

Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::controller(DivisionController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/divisions', 'index');
});

Route::controller(EmployeeController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/employees', 'index');
    Route::post('/employees', 'store');
    Route::put('/employees/{id}', 'update');
    Route::delete('/employees/{id}', 'delete');
});

Route::get('/nilaiRT', [BonusSQL::class, 'nilaiRT']);
Route::get('/nilaiST', [BonusSQL::class, 'nilaiST']);
