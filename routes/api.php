<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\ProfilController;
use App\Http\Controllers\Announcer\PropertyController as PropertyControllerAnnouncer;
use App\Http\Controllers\Api\Visiteur\UserController;
use App\Models\User;

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


Route::get('list/category', [CategoryController::class,'listcategory'])->name('listcategory');
Route::get('list/category/property/{id}', [CategoryController::class,'listcategoryproperty'])->name('listcategoryproperty');

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('getinfo', [ProfilController::class, 'getinfo'])->name('getinfo');
    Route::put('update_info', [ProfilController::class, 'update_info'])->name('update_info');
    Route::put('update_pass', [ProfilController::class, 'update_pass'])->name('update_pass');
    Route::delete('logout', [ProfilController::class, 'logout'])->name('logout');

    Route::middleware(['visitor'])->group(function () {
        Route::put('became_announcer', [ProfilController::class, 'became_announcer'])->name('became_announcer');

        // Favoris
        Route::get('toggle/favoris/{iduser}/{idproperty}', [UserController::class,'togglefavoris'])->name('togglefavoris');
        Route::get('list/favoris/{id}', [UserController::class,'listfavoris'])->name('listfavoris');

        // Propriete
        Route::get('list/last/properties',[UserController::class,'lastproperties'])->name('lastproperties');
Route::get('details/properties/{id}',[UserController::class,'detailsproperties'])->name('detailsproperties');

    });

    Route::middleware(['announcer'])->group(function () {

        Route::get('announcer/property', [PropertyControllerAnnouncer::class, 'index'])->name('announcer.property');
        Route::get('announcer/property/{property}/show', [PropertyControllerAnnouncer::class, 'show'])->name('announcer.property.show');
        Route::get('announcer/property/{property}/action', [PropertyControllerAnnouncer::class, 'action'])->name('announcer.property.action');
        Route::post('announcer/property/create', [PropertyControllerAnnouncer::class, 'create'])->name('announcer.property.create');
        Route::put('announcer/property/{property}/update', [PropertyControllerAnnouncer::class, 'update'])->name('announcer.property.update');

        Route::get('announcer/property/{property}/calendar', [PropertyControllerAnnouncer::class, 'calendar'])->name('announcer.property.calendar');
        Route::post('announcer/property/{property}/add_calendar', [PropertyControllerAnnouncer::class, 'add_calendar'])->name('announcer.property.add_calendar');
        Route::put('announcer/property/{calendar}/update_calendar', [PropertyControllerAnnouncer::class, 'update_calendar'])->name('announcer.property.update_calendar');

        Route::get('announcer/property/{property}/visits', [PropertyControllerAnnouncer::class, 'visits'])->name('announcer.property.visits');
        Route::put('announcer/property/{visit}/action_visit', [PropertyControllerAnnouncer::class, 'action_visit'])->name('announcer.property.action_visit');

    });

    Route::middleware(['admin'])->group(function () {
        // Category end-point
        Route::post('add/category', [CategoryController::class,'addcategory'])->name('addcategory');
        Route::put('update/category/{id}', [CategoryController::class,'updatecategory'])->name('updatecategory');
        Route::get('delete/category/{id}', [CategoryController::class,'deletecategory'])->name('deletecategory');

    });
});
