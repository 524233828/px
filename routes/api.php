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
Route::get("/class/list", "ClassController@fetch");
Route::get("/class/info", "ClassController@get");

//预约
Route::post("/class/appoint", "AppointController@create")->middleware(["jwt.dispatch", "login.check"]);
Route::get("/appoint/list", "AppointController@fetch")->middleware(["jwt.dispatch", "login.check"]);

//评价
Route::post("/comment/class", "CommentController@create")->middleware(["jwt.dispatch", "login.check"]);

// 课程分类
Route::get("/category", "CategoryController@fetchByParentId");
Route::get("/category/list", "CategoryController@fetch");

// 支付/下单
Route::post("/order/unifier", "OrderController@unifier")->middleware(["jwt.dispatch", "login.check"]);
Route::post("/order/pay", "OrderController@pay")->middleware(["jwt.dispatch", "login.check"]);

//用户
Route::get("/user/info", "UserController@info")->middleware(["jwt.dispatch", "login.check"]);
Route::get("/user/code", "UserController@code")->middleware(["jwt.dispatch", "login.check"]);

//常用人
Route::post("/child/save", "ChildController@save")->middleware(["jwt.dispatch", "login.check"]);
Route::get("/child/list", "ChildController@fetch")->middleware(["jwt.dispatch", "login.check"]);
Route::get("/child/info", "ChildController@get")->middleware(["jwt.dispatch", "login.check"]);

//预约卡
Route::get("/card/fetch", "CardController@fetch")->middleware(["jwt.dispatch", "login.check"]);

//店铺
Route::get("/shop/list", "ShopController@fetch");
Route::get("/shop/info", "ShopController@get");

//收藏
Route::post("/collect/create", "CollectController@create")->middleware(["jwt.dispatch", "login.check"]);
Route::post("/collect/cancel", "CollectController@cancel")->middleware(["jwt.dispatch", "login.check"]);
Route::get("/collect/list", "CollectController@fetch")->middleware(["jwt.dispatch", "login.check"]);

//钱包
Route::get("/wallet/info", "WalletController@get")->middleware(["jwt.dispatch", "login.check"]);
Route::post("/wallet/withdraw", "WalletController@withdraw")->middleware(["jwt.dispatch", "login.check"]);
