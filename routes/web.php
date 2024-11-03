<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\ArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api'], function () {

    Route::post('/application', [ApplicationController::class, 'store']);
    Route::post('/articles', [ArticleController::class, 'index']);

});

