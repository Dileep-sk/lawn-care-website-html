<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::post('/profile/update', [ProfileController::class, 'update']);

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users', [UserController::class, 'index']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::put('/users/{id}/status', [UserController::class, 'updateStatus']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::get('/users/{id}', [UserController::class, 'view']);

    Route::get('/stocks', [StockController::class, 'index']);
    Route::get('/stocks/{id}', [StockController::class, 'view']);
    Route::post('/stocks', [StockController::class, 'store']);
    Route::put('/stocks/{id}', [StockController::class, 'update']);
    Route::put('/stocks/{id}/status', [StockController::class, 'updateStatus']);
    Route::delete('/stocks/{id}', [StockController::class, 'destroy']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
