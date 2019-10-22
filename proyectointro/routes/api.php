<?php

use Illuminate\Http\Request;
use App\Http\Resources\Product as ProductResource;

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

Route::get('/greeting', function (Request $request){
    return 'Hellow World!';
});

Route::post('/products', "ProductController@store")->name('product.create');

Route::put('/products/{id}', "ProductController@update")->name('product.update');

Route::delete('/products/{id}', "ProductController@destroy")->name('product.delete');

Route::get('/products/{id}', "ProductController@show")->name('product.show');

Route::get('/products', "ProductController@showAll")->name('products.show');
