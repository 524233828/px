<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 首页banner
Route::get("/banners", "BannerController@fetch");

// 课程
Route::get("/classed", "ClassController@fetch");

// 课程分类
Route::get("/category", "CategoryController@fetchByParentId");

// 支付/下单
Route::post("/order/unifier", "OrderController@unifier")->middleware(["dispatch", "login.check"]);

//用户
Route::get("/user/info", "UserController@info")->middleware(["dispatch", "login.check"]);

//常用人
Route::post("/child/save", "ChildController@save")->middleware(["dispatch", "login.check"]);
Route::get("/child/list", "ChildController@fetch")->middleware(["dispatch", "login.check"]);
Route::get("/child/info", "ChildController@get")->middleware(["dispatch", "login.check"]);


