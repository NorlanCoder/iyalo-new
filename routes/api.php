<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfilController;
use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\DetailController;
use App\Http\Controllers\Api\Admin\AnnonceController;

use App\Http\Controllers\Announcer\PropertyController;
use App\Http\Controllers\Announcer\WithdrawController;

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

    });

    Route::middleware(['announcer'])->group(function () {

        Route::get('announcer/property', [PropertyController::class, 'index'])->name('announcer.property');
        Route::get('announcer/property/{property}/show', [PropertyController::class, 'show'])->name('announcer.property.show');
        Route::get('announcer/property/{property}/action', [PropertyController::class, 'action'])->name('announcer.property.action');
        Route::post('announcer/property/create', [PropertyController::class, 'create'])->name('announcer.property.create');
        Route::put('announcer/property/{property}/update', [PropertyController::class, 'update'])->name('announcer.property.update');

        Route::get('announcer/property/{property}/calendar', [PropertyController::class, 'calendar'])->name('announcer.property.calendar');
        Route::post('announcer/property/{property}/add_calendar', [PropertyController::class, 'add_calendar'])->name('announcer.property.add_calendar');
        Route::put('announcer/property/{calendar}/update_calendar', [PropertyController::class, 'update_calendar'])->name('announcer.property.update_calendar');

        Route::get('announcer/property/{property}/visits', [PropertyController::class, 'visits'])->name('announcer.property.visits');
        Route::put('announcer/property/{visit}/action_visit', [PropertyController::class, 'action_visit'])->name('announcer.property.action_visit');

        // Withdraw
        Route::get('announcer/withdraw/', [WithdrawController::class, 'index'])->name('announcer.withdraw');
        Route::post('announcer/withdraw/create', [WithdrawController::class, 'create'])->name('announcer.withdraw.create');
    });

    Route::middleware(['admin'])->group(function () {
        // Category end-point
        Route::post('add/category', [CategoryController::class,'addcategory'])->name('addcategory');
        Route::put('update/category/{id}', [CategoryController::class,'updatecategory'])->name('updatecategory');
        Route::get('delete/category/{id}', [CategoryController::class,'deletecategory'])->name('deletecategory');

        // Usercontroller
        Route::get('admin/', [UserController::class,'index'])->name('admin');
        Route::post('admin/create', [UserController::class,'create'])->name('admin.create');
        Route::put('admin/{user}/update', [UserController::class,'update'])->name('admin.update');
        Route::get('admin/{user}/action', [UserController::class,'action'])->name('admin.action');
        Route::get('admin/{user}/user_withdraw', [UserController::class,'user_withdraw'])->name('admin.user_withdraw');

        // DetailController
        Route::get('admin/properties', [DetailController::class,'properties'])->name('admin.properties');
        Route::get('admin/categories', [DetailController::class,'categories'])->name('admin.categories');
        Route::get('admin/withdraws', [DetailController::class,'withdraws'])->name('admin.withdraws');
        Route::get('admin/{withdraw}/valide_withdrawal', [DetailController::class,'valide_withdrawal'])->name('admin.valide_withdrawal');

        // AnnonceController
        Route::get('admin/annonces', [AnnonceController::class,'index'])->name('admin.annonces');
        Route::post('admin/annonces/create', [AnnonceController::class,'create'])->name('admin.annonces.create');
        Route::put('admin/annonces/{annonce}/update', [AnnonceController::class,'update'])->name('admin.annonces.update');
        Route::get('admin/annonces/{annonce}/action', [AnnonceController::class,'action'])->name('admin.annonces.action');
        Route::delete('admin/annonces/{annonce}/destroy', [AnnonceController::class,'destroy'])->name('admin.annonces.destroy');






    });
});
