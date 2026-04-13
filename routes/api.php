<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserCategoryController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('user-categories')->group(function () {
    Route::get('/flat',               [UserCategoryController::class, 'flat']);
    Route::get('/',                   [UserCategoryController::class, 'index']);
    Route::get('/{userCategory}',     [UserCategoryController::class, 'show']);
    Route::post('/',                  [UserCategoryController::class, 'store']);
    Route::put('/{userCategory}',     [UserCategoryController::class, 'update']);
    Route::delete('/{userCategory}',  [UserCategoryController::class, 'destroy']);
    Route::post('/insert-between',    [UserCategoryController::class, 'insertBetween']);
});