<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    /** @var \Illuminate\Cache\Repository $cache */
//    $cache = \Illuminate\Support\Facades\Cache::store("redis");
//    /** @var \Illuminate\Cache\RedisStore $store */
//    $store = $cache->getStore();
//    /** @var \Illuminate\Redis\RedisManager $manager */
//    $manager = $store->getRedis();
//
//    $redis = \Illuminate\Support\Facades\Redis::connection("default");
//    var_dump($redis->client());exit;
    return view('welcome');
});
