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

Route::get('/', "BucketController@index");
Route::post("/add", "BucketController@add")->name("bucket.add");
Route::get("/file/{path?}", "BucketController@download")
    ->name("bucket.file")
    ->where('path', '(.*)');

Route::get("{hash?}", "BucketController@shortLink")
    ->name("short_link");
