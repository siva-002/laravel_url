<?php

use App\Http\Controllers\UrlShortenerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// for intial request to get token 
Route::get('/', [UrlShortenerController::class, 'index']);
// Route::get('/notfound', [UrlShortenerController::class, 'notfound']);
Route::get('/getcsrf', [UrlShortenerController::class, 'getCsrf']);

// for url get and post

Route::post("/addUrl", [UrlShortenerController::class, 'storeUrl']);
Route::get("/{url}", [UrlShortenerController::class, 'redirectUrl']);
Route::post("/deleteUrl", [UrlShortenerController::class, 'deleteUrl']);


// for user register and login
Route::post("/user/register", [UserController::class, "createUser"]);
Route::post("/user/guestcreate", [UserController::class, "guestUserCreate"]);
Route::post("/user/login", [UserController::class, "loginUser"]);
Route::post("/user/getUrlData", [UrlShortenerController::class, 'getUrlData']);
Route::middleware(['Authmiddleware'])->group(function () {
    Route::post("/user/logout", [UserController::class, "logoutUser"]);
});
