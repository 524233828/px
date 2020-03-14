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
Route::get("/class/list/byShop", "ClassController@fetchByShopId");
Route::get("/class/info", "ClassController@get")->middleware(["jwt.dispatch", "login.check"]);

//预约
Route::post("/class/appoint", "AppointController@create")->middleware(["jwt.dispatch", "login.check"]);
Route::get("/appoint/list", "AppointController@fetch")->middleware(["jwt.dispatch", "login.check"]);
Route::get("/appoint", "AppointController@get")->middleware(["jwt.dispatch", "login.check"]);

//评价
Route::post("/comment/class", "CommentController@create")->middleware(["jwt.dispatch", "login.check"]);
Route::get("/comment/list", "CommentController@fetch");

// 课程分类
Route::get("/category", "CategoryController@fetchByParentId");
Route::get("/category/list", "CategoryController@fetch");

// 支付/下单
Route::post("/order/unifier", "OrderController@unifier")->middleware(["jwt.dispatch", "login.check"]);
Route::post("/order/pay", "OrderController@pay")->middleware(["jwt.dispatch", "login.check"]);
Route::get("/order/list", "OrderController@fetch")->middleware(["jwt.dispatch", "login.check"]);
Route::get("/order", "OrderController@get")->middleware(["jwt.dispatch", "login.check"]);
Route::post("/order/notify", "OrderController@notify");

//用户
Route::get("/user/info", "UserController@info")->middleware(["jwt.dispatch", "login.check"]);
Route::get("/user/code", "UserController@code")->middleware(["jwt.dispatch", "login.check"]);
Route::post("/user/update", "UserController@update")->middleware(["jwt.dispatch", "login.check"]);
Route::post("/user/bind", "UserController@bindCode")->middleware(["jwt.dispatch", "login.check"]);

//常用人
Route::post("/child/save", "ChildController@save")->middleware(["jwt.dispatch", "login.check"]);
Route::get("/child/list", "ChildController@fetch")->middleware(["jwt.dispatch", "login.check"]);
Route::get("/child/info", "ChildController@get")->middleware(["jwt.dispatch", "login.check"]);
Route::post("/child/delete", "ChildController@delete")->middleware(["jwt.dispatch", "login.check"]);

//预约卡
Route::get("/card/fetch", "CardController@fetch")->middleware(["jwt.dispatch", "login.check"]);
Route::get("/card/info", "CardController@info")->middleware(["jwt.dispatch"]);
Route::get("/card/list", "CardController@listCard")->middleware(["jwt.dispatch"]);

//店铺
Route::get("/shop/list", "ShopController@fetch");
Route::get("/shop/info", "ShopController@get")->middleware(["jwt.dispatch", "login.check"]);

//收藏
Route::post("/collect/create", "CollectController@create")->middleware(["jwt.dispatch", "login.check"]);
Route::post("/collect/cancel", "CollectController@cancel")->middleware(["jwt.dispatch", "login.check"]);
Route::get("/collect/list", "CollectController@fetch")->middleware(["jwt.dispatch", "login.check"]);

//钱包
Route::get("/wallet/info", "WalletController@get")->middleware(["jwt.dispatch", "login.check"]);
Route::post("/wallet/withdraw", "WalletController@withdraw")->middleware(["jwt.dispatch", "login.check"]);

//地区
Route::get("/area", "AreaController@getAreaByParentArea");

//账单
Route::get("/bill/list", "BillController@fetch")->middleware(["jwt.dispatch", "login.check"]);

//商品
Route::get("/goods/list", "GoodsController@fetch")->middleware(["jwt.dispatch", "login.check"]);
Route::get("/goods/details", "GoodsController@get")->middleware(["jwt.dispatch", "login.check"]);

//系统配置
Route::get("/config", "ConfigController@get");

//老师
Route::get("/teacher/info", "TeacherController@get");
//专栏
Route::get("/special/list", "SpecialController@fetch");
Route::get("/special_class/list", "SpecialClassController@fetch");
Route::get("/special_class/info", "SpecialClassController@get");
Route::post("/special_class/play", "SpecialClassController@play");
