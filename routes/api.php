<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrokerController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DesignNoController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\MarkNoController;
use App\Http\Controllers\Api\TransportCompanyController;

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

    Route::get('/out-of-stocks', [StockController::class, 'outOffStock']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/latest-id', [OrderController::class, 'getLatestOrderId']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
    Route::get('/order_id', [OrderController::class, 'getOrderId'])->name('getOrder');

    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::put('/jobs/{id}/status', [JobController::class, 'updateStatus']);
    Route::delete('/jobs/{id}', [JobController::class, 'destroy']);
    Route::post('/jobs', [JobController::class, 'store']);
    Route::get('/jobs/{id}', [JobController::class, 'show']);

    Route::get('/mark_no', [MarkNoController::class, 'index'])->name('mark_no.index');
    Route::get('/design_no', [DesignNoController::class, 'index'])->name('design_no.index');

    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/broker', [BrokerController::class, 'index'])->name('broker.index');
    Route::get('/transport-company', [TransportCompanyController::class, 'index'])->name('broker.index');

    Route::get('/design-no/{id}', [StockController::class, 'availableStock'])->name('availableStock');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
