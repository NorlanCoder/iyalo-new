<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AnnouncerController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\VisitController;
use App\Http\Controllers\Admin\WithdrawController;
use App\Http\Controllers\Admin\AnnonceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


//AuthController        
Route:: get('/', [AuthController::class, 'login'])->name('auth.login');
Route::post('/check', [AuthController::class, 'check'])->name('auth.check');

Route:: get('/forget', [AuthController::class, 'forget'])->name('auth.forget');
Route::get('/reset', [AuthController::class, 'reset'])->name('auth.reset');
Route::get('/link-sended', [AuthController::class, 'linkSended'])->name('auth.link-sended');
Route::post('/exist', [AuthController::class, 'exist'])->name('auth.exist');
Route::put('/update_pass/{id}', [AuthController::class, 'update_pass'])->name('auth.update_pass');

Route::group(['middleware' => ['auth']],function () {
        
    Route:: get('/profil', [DashboardController::class, 'profil'])->name('admin.profil');
    Route:: put('/profil/update_info/{id}', [DashboardController::class, 'update_info'])->name('admin.update_info');
    Route:: put('/profil/update_pass/{id}', [DashboardController::class, 'update_pass'])->name('admin.update_pass');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/notes', [DashboardController::class, 'notes'])->name('admin.notes');

    // Admins
    Route::get('/admins', [AdminController::class, 'index'])->name('admin.admins');
    Route::post('/admins/create', [AdminController::class, 'create'])->name('admin.admins.create');
    Route::put('/admins/{user}/update', [AdminController::class, 'update'])->name('admin.admins.update');
    Route::put('/admins/{user}/action', [AdminController::class, 'action'])->name('admin.admins.action');

    // Announcers
    Route::get('/announcers', [AnnouncerController::class, 'index'])->name('admin.announcers');
    Route::put('/announcers/{user}/action', [AnnouncerController::class, 'action'])->name('admin.announcers.action');
    Route::get('/announcers/{user}/properties', [AnnouncerController::class, 'properties'])->name('admin.announcers.properties');
    Route::get('/announcers/{user}/visits', [AnnouncerController::class, 'visits'])->name('admin.announcers.visits');
    Route::get('/announcers/{user}/wallets', [AnnouncerController::class, 'wallets'])->name('admin.announcers.wallets');
    Route::put('/announcers/{user}/percent', [AnnouncerController::class, 'percent'])->name('admin.announcers.percent');

    // Clients
    Route::get('/clients', [ClientController::class, 'index'])->name('admin.clients');
    Route::put('/clients/{user}/action', [ClientController::class, 'action'])->name('admin.clients.action');
    Route::get('/clients/{user}/visits', [ClientController::class, 'visits'])->name('admin.clients.visits');
    
    // Admins
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::post('/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::put('/categories/{category}/update', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::put('/categories/{category}/action', [CategoryController::class, 'action'])->name('admin.categories.action');

    // Properties
    Route::get('/properties', [PropertyController::class, 'index'])->name('admin.properties');
    Route::put('/properties/{property}/action', [PropertyController::class, 'action'])->name('admin.properties.action');

    // Visits
    Route::get('/visits', [VisitController::class, 'index'])->name('admin.visits');
    Route::put('/visits/{visit}/refund', [VisitController::class, 'refund'])->name('admin.visits.refund');
    Route::put('/visits/{visit}/check', [VisitController::class, 'check'])->name('admin.visits.check');

    // Withdraws
    Route::get('/withdraws', [WithdrawController::class, 'index'])->name('admin.withdraws');
    Route::put('/withdraws/{withdraw}/check', [WithdrawController::class, 'check'])->name('admin.withdraws.check');

    // Annonces
    Route::get('/annonces', [AnnonceController::class, 'index'])->name('admin.annonces');
    Route::post('/annonces/create', [AnnonceController::class, 'create'])->name('admin.annonces.create');
    Route::put('/annonces/{annonce}/update', [AnnonceController::class, 'update'])->name('admin.annonces.update');
    Route::put('/annonces/{annonce}/action', [AnnonceController::class, 'action'])->name('admin.annonces.action');

    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});
