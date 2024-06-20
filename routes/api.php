<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfilController;
use App\Http\Controllers\Announcer\PropertyController as PropertyControllerAnnouncer;

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

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('getinfo', [ProfilController::class, 'getinfo'])->name('getinfo');
    Route::put('update_info', [ProfilController::class, 'update_info'])->name('update_info');
    Route::put('update_pass', [ProfilController::class, 'update_pass'])->name('update_pass');
    
    Route::middleware(['visitor'])->group(function () {
        Route::put('became_announcer', [ProfilController::class, 'became_announcer'])->name('became_announcer');

    });

    Route::middleware(['announcer'])->group(function () {

        Route::get('announcer/', [PropertyControllerAnnouncer::class, 'index'])->name('announcer');
        Route::get('announcer/{property}/show', [PropertyControllerAnnouncer::class, 'show'])->name('announcer.show');
        Route::get('announcer/{property}/action', [PropertyControllerAnnouncer::class, 'action'])->name('announcer.action');
        Route::post('announcer/create', [PropertyControllerAnnouncer::class, 'create'])->name('announcer.create');
        Route::put('announcer/{property}/update', [PropertyControllerAnnouncer::class, 'update'])->name('announcer.update');
        
    });
});
