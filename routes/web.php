<?php

use App\Http\Controllers\UrlShortenerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UrlShortenerController::class, 'index']);
Route::get('/getcsrf', [UrlShortenerController::class, 'getCsrf']);

Route::post("/addUrl", [UrlShortenerController::class, 'storeUrl']);

Route::get("/{url}", [UrlShortenerController::class, 'redirectUrl']);
Route::post("/deleteUrl", [UrlShortenerController::class, 'deleteUrl']);
