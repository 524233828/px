<?php

use Illuminate\Routing\Router;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\Route;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->resource("/banners", 'BannerController');
    $router->resource("/classes", 'ClassController');
    $router->resource("/category", 'CategoryController');
    $router->resource("/goods", 'GoodsController');
    $router->resource("/shops", 'ShopController');
    $router->resource("/orders", 'OrderController');
    $router->resource("/wallet", 'WalletController');
    $router->resource("/withdraw", 'WithdrawController');
    $router->resource("/user", 'UserController');
    $router->resource("/config", 'ConfigController');
    $router->post("/wangeditor/upload", 'WangEditorController@save');
    $router->resource("/appoint", 'AppointController');

});


