<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Middleware\CheckApiToken;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('welcome');
});


//для токена
Route::prefix('api')->get('/csrf-token', function () {
    //$token = csrf_token();
    //$token = Session::getId();
    //return response()->json(['csrfToken' => $token]);
});

Route::group(['prefix' => 'api', 'middleware' => ['throttle:api', CheckApiToken::class]], function () {



    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{id}', [ArticleController::class, 'get']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/services', [ServiceController::class, 'index']);

    Route::post('/application/store', [ApplicationController::class, 'store']);
});


