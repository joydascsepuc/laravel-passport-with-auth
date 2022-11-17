<?php

use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\PostController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);

// posts methods without auth
Route::get('/get-all-posts', [PostController::class, 'index']);
Route::get('/get-single-post', [PostController::class, 'show']);


Route::middleware('auth:api')->group(function () {

    Route::get('/logout', [PassportAuthController::class, 'logout']);

    Route::get('/get-my-posts', [PostController::class, 'getMyPosts']);
    Route::post('/create-post', [PostController::class, 'store']);
    Route::post('/update-post', [PostController::class, 'update']);
    Route::get('/delete-post/{id}', [PostController::class, 'destroy']);

});