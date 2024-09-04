<?php
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UrlShortenerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

use Encore\Admin\Facades\Admin;


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
Route::middleware(['Authmiddleware', 'UpgradePlanMiddleware'])->group(function () {
});

Route::post('/payment/stripe', [StripeController::class, 'stripe'])->name('stripe');
Route::get('/payment/success', [StripeController::class, 'success'])->name('success');
Route::get('/payment/cancel', [StripeController::class, 'cancel'])->name('cancel');

Route::get('/product/listpricing', [ProductController::class, 'getAllPrices'])->name('listpricing');

// admin routes
Route::middleware(['admin'])->group(function () {
    Route::get('admin/dashboard', [DashboardController::class, "index"]);
});

