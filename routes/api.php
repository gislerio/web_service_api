<?php

use App\Http\Controllers\v1\AuthApiController;
use App\Http\Controllers\v1\CategoryController;
use App\Http\Controllers\v1\ProductController;
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

/* 
Route::get('categories', [CategoryController::class, 'index']);
Route::post('categories', [CategoryController::class, 'store']);
Route::put('categories/{id}', [CategoryController::class, 'update']);
Route::delete('categories/{id}', [CategoryController::class, 'delete']); 
*/

Route::post('v1/auth', [AuthApiController::class, 'authenticate']);
Route::prefix('v1')->middleware('jwt.auth')->group(function () {
    Route::post('auth-refresh', [AuthApiController::class, 'refreshToken']);
    Route::get('me', [AuthApiController::class, 'getAuthenticatedUser']);
    
    Route::get('categories/{id}/products', [CategoryController::class, 'products']);

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
});
