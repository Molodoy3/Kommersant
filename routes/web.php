<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Middleware\CheckApiToken;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


//для токена


Route::group(['prefix' => 'api', 'middleware' => ['throttle:api', CheckApiToken::class]], function () {

    //токен для аутентификации post и прочих запросов
    Route::get('/csrf-token', function () {
        $token = csrf_token();
        return $token;
    });

    //новости
    Route::get('/articles', [ArticleController::class, 'index']);
    //одна новость
    Route::get('/articles/{id}', [ArticleController::class, 'get']);

    //категории для услуг
    Route::get('/categories', [CategoryController::class, 'index']);
    //услуги
    Route::get('/services', [ServiceController::class, 'index']);

    //объекты недвижимости
    Route::get('/properties-recent', [PropertyController::class, 'recent']);
    Route::get('/property/{id}', [PropertyController::class, 'get']);

    //сохранение заявки
    Route::post('/application/store', [ApplicationController::class, 'store']);
});


