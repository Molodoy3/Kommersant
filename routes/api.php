<?php

use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'middleware' => ['throttle:api']], function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/home', [AdminController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/check-api-token', [UserController::class, 'checkApiToken'])->middleware('auth:sanctum');
});

Route::group(['middleware' => ['throttle:api']], function () {

    //токен для аутентификации post и прочих запросов
    Route::get('/csrf-token', function () {
        $token = csrf_token();
        return $token;
    });

    //новости
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{id}', [ArticleController::class, 'get']);
    Route::post('/articles', [ArticleController::class, 'create'])->middleware('auth:sanctum');
    Route::patch('/articles/{id}', [ArticleController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/articles/{id}', [ArticleController::class, 'delete'])->middleware('auth:sanctum');

    //категории для услуг
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'get']);
    Route::post('/categories', [CategoryController::class, 'create'])->middleware('auth:sanctum');
    Route::patch('/categories/{id}', [CategoryController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/categories/{id}', [CategoryController::class, 'delete'])->middleware('auth:sanctum');
    //услуги
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/{id}', [ServiceController::class, 'get']);
    Route::post('/services', [ServiceController::class, 'create'])->middleware('auth:sanctum');
    Route::patch('/services/{id}', [ServiceController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/services/{id}', [ServiceController::class, 'delete'])->middleware('auth:sanctum');

    //объекты недвижимости
    Route::get('/properties-recent', [PropertyController::class, 'recent']);
    Route::get('/properties/{id}', [PropertyController::class, 'get']);
    Route::get('/properties', [PropertyController::class, 'index']);
    Route::post('/properties', [PropertyController::class, 'create'])->middleware('auth:sanctum');
    Route::patch('/properties/{id}', [PropertyController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/properties/{id}', [PropertyController::class, 'delete'])->middleware('auth:sanctum');

    Route::get('/info-by-properties/', [PropertyController::class, 'infoByProperties']);

    //сохранение заявки
    Route::post('/application/store', [ApplicationController::class, 'store']);
});
