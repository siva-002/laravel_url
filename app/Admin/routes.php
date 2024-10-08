<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
    'as' => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('users', UserController::class);
    $router->resource('status', StatusController::class);
    $router->resource('urls', UrlController::class);
    $router->resource('userids', UserIdController::class);
    $router->resource('stripeinfos', StripeinfoController::class);
    $router->resource('payments', PaymentController::class);
    $router->resource('subscription-models', SubscriptionController::class);
    $router->resource('stripe-products', StripeProductController::class);
    $router->resource('stripe-prices', StripePricesController::class);
});
