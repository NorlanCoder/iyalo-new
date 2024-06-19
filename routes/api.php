<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\CategoryController;

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

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('forget', [AuthController::class, 'forget'])->name('forget');
Route::post('validate_token', [AuthController::class, 'validate_token'])->name('validate_token');
Route::post('reset', [AuthController::class, 'reset'])->name('reset');

// Category end-point
Route::get('list/category', [CategoryController::class,'listcategory'])->name('listcategory');
Route::get('list/category/property', [CategoryController::class,'listcategoryproperty'])->name('listcategoryproperty');

Route::middleware(['auth:sanctum'])->group(function () {

    Route::middleware(['visitor'])->group(function () {

    });

    Route::middleware(['announcer'])->group(function () {

    });
});
