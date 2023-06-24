<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::prefix('category')->group(function(){

    Route::middleware('api', 'auth')->group(function () {
        Route::post('add', [CategoryController::class, 'store']);
        Route::put('update/{id}', [CategoryController::class, 'update']);
        Route::delete('delete/{id}', [CategoryController::class, 'destroy']);
    });
    
    Route::get('list', [CategoryController::class, 'index']);
    Route::get('detail/{id}', [CategoryController::class, 'show']);

   

});

Route::prefix('product')->group(function(){
    Route::get('list', [ProductController::class, 'index']);
    Route::get('detail/{id}', [ProductController::class, 'show']);

    Route::group([ 'middleware' => 'api', 'auth', 'CheckLogin'], function () {
        Route::post('add', [ProductController::class, 'store']);
        Route::put('update/{id}', [ProductController::class, 'update']);
        Route::delete('delete/{id}', [ProductController::class, 'destroy']);
    });
});

Route::middleware('api', 'auth','CheckLogin')->prefix('cart')->group(function(){
    Route::post('/add', [CartController::class, 'store']);
    Route::put('update/{id}', [CartController::class, 'update']);
    Route::delete('delete/{id}', [CartController::class, 'destroy']);
});

Route::middleware('api', 'auth','CheckLogin')->prefix('order')->group(function(){
    Route::post('/add', [OrderController::class, 'store']);
    Route::put('update/{orderCode}', [OrderController::class, 'updateStatus']);
    Route::delete('delete/{id}', [OrderController::class, 'destroy']);
});

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login',[AuthController::class,'login']);
    Route::get('login',[AuthController::class,'FormLogin'])->name('login');
    Route::post('register',[AuthController::class,'register']);
    Route::post('logout',[AuthController::class,'logout']);
    Route::post('refresh',[AuthController::class,'refresh']);
    Route::post('me',   [AuthController::class,'me']);
});


