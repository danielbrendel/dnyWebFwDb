<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\FrameworkController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [MainController::class, 'index']);
Route::post('/register', [MainController::class, 'register']);
Route::get('/confirm', [MainController::class, 'confirm']);
Route::get('/reset', [MainController::class, 'viewReset']);
Route::post('/recover', [MainController::class, 'recover']);
Route::get('/resend/{id}', [MainController::class, 'resend']);
Route::post('/reset', [MainController::class, 'reset']);
Route::post('/login', [MainController::class, 'login']);
Route::any('/logout', [MainController::class, 'logout']);
Route::post('/framework/query', [FrameworkController::class, 'query']);
Route::post('/framework/query/user', [FrameworkController::class, 'queryUser']);
Route::post('/framework/query/reviews', [FrameworkController::class, 'queryReviews']);
Route::get('/view/{framework}', [FrameworkController::class, 'view']);
Route::get('/user/{ident}', [MemberController::class, 'showProfile']);
Route::get('/member/name/valid', [MemberController::class, 'usernameValidity']);
Route::get('/admin', [AdminController::class, 'index']);
Route::post('/admin/about/save', [AdminController::class, 'saveAbout']);
Route::post('/admin/logo/save', [AdminController::class, 'saveLogo']);
Route::post('/admin/cookieconsent/save', [AdminController::class, 'saveCookieConsent']);
Route::post('/admin/reginfo/save', [AdminController::class, 'saveRegInfo']);
Route::post('/admin/tos/save', [AdminController::class, 'saveTosContent']);
Route::post('/admin/imprint/save', [AdminController::class, 'saveImprintContent']);
Route::post('/admin/headcode/save', [AdminController::class, 'saveHeadCode']);
Route::get('/admin/user/details', [AdminController::class, 'userDetails']);
Route::post('/admin/user/save', [AdminController::class, 'userSave']);
Route::any('/admin/user/{id}/resetpw', [AdminController::class, 'userResetPassword']);
Route::any('/admin/user/{id}/delete', [AdminController::class, 'userDelete']);
Route::any('/admin/user/{id}/lock', [AdminController::class, 'lockUser']);
Route::any('/admin/user/{id}/safe', [AdminController::class, 'setUserSafe']);
